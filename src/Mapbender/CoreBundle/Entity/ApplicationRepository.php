<?php
    
    
    namespace Mapbender\CoreBundle\Entity;
    
    
    use Doctrine\ORM\EntityRepository;
    
    use Mapbender\CoreBundle\Entity\Application;
    use Mapbender\WmsBundle\Entity\WmsInstance;
    use Mapbender\WmsBundle\Entity\WmsSource;
    use Mapbender\CoreBundle\Entity\SourceInstance;

    class ApplicationRepository extends EntityRepository
    {
        public function findBySourceId($sourceId){
        
            $qb = $this->createQueryBuilder('MA');
            $result = $qb
                //  ->addSelect('*')
                //->from("mb_core_layerset","mcl" )
                ->join(Layerset::class,"mls", \Doctrine\ORM\Query\Expr\Join::WITH,"mls.application = MA.id")
                //->join("mb_core_application", "mca", "on", "mb_core_layerset.application_id = mca.id")
                ->join(WmsInstance::class, "mwi",  \Doctrine\ORM\Query\Expr\Join::WITH,"mls.id = mwi.id")
                ->where($qb->expr()->eq("mwi.source", ":sourceId"))
                ->setParameter("sourceId",$sourceId)
                ->getQuery()
                ->execute()
            ;
        
            return $result;
            //SELECT * FROM mb_core_layerset JOIN mb_core_sourceinstance mcs on mb_core_layerset.id = mcs.layerset join mb_core_application mca on mb_core_layerset.application_id = mca.id join mb_wms_wmsinstance mww on mcs.id = mww.id WHERE wmssource = 1;
        }
    }