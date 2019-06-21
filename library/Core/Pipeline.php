<?php

namespace Mini\Core;

use Mini\Core\Container;
class Pipeline {

    protected $passable;

    protected $pips = [];

    public function __construct(Container $container)
    {
        $this->app = $container;
    }


    public function send($passable) {
        $this->passable = $passable;
        return $this;
    }

    public function through($pips) {
        $this->pips = is_array($pips) ? $pips : func_get_args();
        return $this;
    }

    protected function carry() {
        //array_reduce使用的闭包
        return function($stack, $pip) {
            //array_reduce压缩起来的闭包函数栈是这一层
            return function($passable) use($stack, $pip) {
                $pip = is_string($pip) ? $this->app->make($pip) : $pip;
                return $pip->handle($passable, $stack);
            };
        };

    }

    protected function prepareDestination($destination) {
        return function($passable) use ($destination){
            return $destination($passable);
        };
    } 


    public function then(Closure $destination) {
        //通过array_reduce方法把pips压缩成闭包栈：
        //return function($passable) {
        //     return $pip1->handle($passable, function($passable) {
        //          return function($passable) {
        //                 return $pip2->handle($passable, function($passable) {
        //                          return function($passable) {
        //                                  return $destination->handle($passable, function($passable){
        //                                         //destination方法    
        //                                  });
        //                          };
        //                 });
        //          };
        //     });
        //};
        //执行时通过传入handle的第二个闭包函数就可以层层调用:$next($passable);
        $pipline = array_reduce(array_reverse($this->pips), $this->carry(), $this->prepareDestination($destination));
        return $pipline($this->passable);
    }
}