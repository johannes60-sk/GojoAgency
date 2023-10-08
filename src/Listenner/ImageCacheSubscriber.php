<?php

namespace App\Listener;
use App\Entity\Property;
use App\Entity\Picture;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs as EventLifecycleEventArgs;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

// apres avoir definir notre subscriber il faut aller modifier le focher service.yaml pour l'ajouter et 
// permettre a symfony de la charger
class ImageCacheSubscriber implements EventSubscriber {    

    private $cacheManager;
    private $uploaderHelperl;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper){

        $this->cacheManager = $cacheManager;
        $this->uploaderHelperl = $uploaderHelper;
    }

    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    public function preRemove(EventLifecycleEventArgs $args)
    {
        $entity = $args->getEntity() ;

        if(!$entity instanceof Picture){
            return ;
        }
        $this->cacheManager->remove($this->uploaderHelperl->asset($entity, 'imageFile'));


    }

    public function preUpdate(PreUpdateEventArgs $args)
    {

        $entity = $args->getEntity();

        if(!$entity instanceof Picture){

            return ;
        }

        if($entity->getImageFile() instanceof UploadedFile){

            $this->cacheManager->remove($this->uploaderHelperl->asset($entity, 'imageFile'));

        }
    }

}