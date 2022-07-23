<?php

namespace ICS\ToolsBundle\EntityListener;

use Doctrine\ORM\Event\LoadClassMetadataEventArgs;
use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Driver\PDO\MySQL\Driver as PdoMysqlDriver;
use Doctrine\DBAL\Driver\Mysqli\Driver as MysqliDriver;

class MappingListener
{
    public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs)
    {
        $entityManager = $eventArgs->getEntityManager();
        $classMetadata = $eventArgs->getClassMetadata();
        $table = $classMetadata->table;
        $driver = $eventArgs->getEntityManager()->getConnection()->getDriver()->getDatabasePlatform();
        $database = $entityManager->getConnection()->getDatabase();
        
        if(is_a($driver,MySQLPlatform::class ))
        {
            $table['schema']=$database;
        }

        $classMetadata->setPrimaryTable($table);
    }
}