<?php
namespace Mini\Router;
use Symfony\Component\Routing\Annotation\Route as AnnotationRoute;


/**
 * Annotation class for @Route().
 *
 * @Annotation
 * @Target({"CLASS", "METHOD"})
 *
 */
class Route extends AnnotationRoute 
{
    public function __construct(array $data)
    {
        if (isset($data['group'])) {
            $data['defaults']['_group'] = $data['group'];
            unset($data['group']);
        }

        if (isset($data['middleware'])) {
            $data['defaults']['_middleware'] = $data['middleware'];
            unset($data['middleware']);
        }
        parent::__construct($data);
    }
}