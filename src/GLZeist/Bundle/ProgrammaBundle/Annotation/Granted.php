<?php
namespace GLZeist\Bundle\ProgrammaBundle\Annotation;
/**
 * @Annotation
 * @Target({"CLASS","METHOD"})
 */
class Granted {
    
    public $role;
}
