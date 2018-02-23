<?php
/**
 * Created by PhpStorm.
 * User: baotrannhat
 * Date: 2/12/18
 * Time: 2:17 PM
 */
namespace AppBundle\Command;

use AppBundle\AppBundle;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use AppBundle\Entity\TestingUser;
use AppBundle\Entity\Job;
use AppBundle\Entity\JobAlert;
use Symfony\Component\Ldap\Adapter\ExtLdap\Query;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Component\Cache\Adapter\AdapterInterface;


class SendJobAlertCommand extends Command
{
    private $em;
    private $redisAdapter;
    const PERIOD_TO_SEND_JOBALERT =2;
    const OFFSET = 0;
    const LENGTH = 10;
    const ALGOLIA_API_KEY= '0ac4987e3f266705696238f3b1066436';
    const ALGOLIA_APP_ID = 'JF8Q26WWUD';
    const ALGOLIA_INDEX = 'vnw_job_new_staging_ja';
    const REDIS_CONNECTION = 'redis://172.16.4.24:6379';

    public function __construct(EntityManagerInterface $em, AdapterInterface $redisAdapter)
    {
        $this->em = $em;
        $this->redisAdapter = $redisAdapter;
        parent::__construct();
    }
    protected function configure()
    {
        // ...
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:send-job-alert')

            // the short description shown while running "php bin/console list"
            ->setDescription('Use this command to send job alert')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp('This command allows you to send job alert')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $algoliaClient = new \AlgoliaSearch\Client(self::ALGOLIA_APP_ID, self::ALGOLIA_API_KEY);
        $index = $algoliaClient->initIndex(self::ALGOLIA_INDEX);
        //$client = $this->redisAdapter
        $client = RedisAdapter::createConnection(self::REDIS_CONNECTION);
        $startTime = (new \DateTime())->format('Y-m-d H:i:s');
        $query = $this->em->createQuery(
            "SELECT ja
            FROM AppBundle:JobAlert ja
            JOIN AppBundle:TestingUser tu
            WITH ja.userId = tu. userId
            WHERE ja.sendingDate>='".$startTime."' AND ja.sendingDate < 'DATE_ADD($startTime, INTERVAL ".self::PERIOD_TO_SEND_JOBALERT." MINUTE)' AND ja.noOfDays >0"
        );
        $result = $query->getResult();
        foreach ($result as $jobalert) {
            $listJobsSent = json_decode($client->get('job_alert_'.$jobalert->getjobAlertId()),true);
            $query = $this->em->createQuery(
                "SELECT app.jobId
                FROM AppBundle:JobApplication as app
                JOIN AppBundle:Job as j WITH app.jobId = j.jobId
                WHERE app.userId =".$jobalert->getUserId()."
                AND j.isCompleted=1
                AND j.isApproved=1
                AND j.isActive=1
                AND j.isDeleted=0
                AND j.expiredDate>=CURRENT_TIMESTAMP()"
            );
            $appliedJobs = $query->getResult();
            $appliedJobIds = array();
            foreach ($appliedJobs as $job)
            {
                array_push($appliedJobIds,$job['jobId']);
            }
            $excludeJobs = array();
            if (!empty($listJobsSent)) {
                $excludeJobs = array_unique(array_merge($appliedJobIds, $listJobsSent));
            }

            $searchCondition = array(
                'keyword' => $jobalert->getKeyword(),
                'joblevel' => $jobalert->getJoblevelId(),
                'industry' => $jobalert->getIndustryId()
            );

            // Eliminate Expired or Disabled Jobs
            $unavailableJobsFilter = 'jobId > 0 AND companyId > 0 AND expiredDate >=' . strtotime(date("Y-m-d"));

            // Eliminate User Applied Jobs
            if (!empty($excludeJobs)) {
                $appliedJobFilter = implode(' AND ', array_map(function($jobId){ return 'jobId != '.$jobId ; }, $excludeJobs));
            } else {
                $appliedJobFilter = null;
            }

            // Add Job Level filter
            if (!empty($searchCondition['joblevel'])) {
                $jobLevelFilter = 'jobLevelId = ' . $searchCondition['joblevel'];
            }

            // Add Job Category filter using OR operator for combination
            $categories  = $searchCondition['industry'];
            if (count($categories) > 0) {
                $jobCategoryFilter = array_map(function($category) {
                    return 'categoryIds:' . $category;
                }, $categories);

                $jobCategoryFilter = implode(' OR ', $jobCategoryFilter);
                $jobCategoryFilter = '(' . $jobCategoryFilter . ')';
            }

            $filters = (isset($unavailableJobsFilter) ? $unavailableJobsFilter : '')
                . (isset($jobLevelFilter) ? ' AND ' . $jobLevelFilter : '')
                . (isset($appliedJobFilter) ? ' AND ' . $appliedJobFilter : '')
                . (isset($jobCategoryFilter) ? ' AND ' . $jobCategoryFilter : '')
            ;

            $retrieveFields = [
                'jobId',
                'userId',
                'categoryIds',
                'skills',
                'locationIds',
                'jobTitle',
            ];

            $totalJobs = $index->search($searchCondition['keyword'], [
                'filters' => $filters,
                'offset' => self::OFFSET,
                'length' => self::LENGTH,
                'attributesToRetrieve' => $retrieveFields,
            ]);
            if(!empty($totalJobs['hits']))
            {
                $newSentJobs = array();
                foreach ($totalJobs['hits'] as $job)
                {
                    array_push($newSentJobs,$job['jobId']);
                }
                //update the list sent jobs
                $client->set('job_alert_'.$jobalert->getjobAlertId(),implode(",",array_merge($newSentJobs,$listJobsSent)));
            }
        }
    }
}