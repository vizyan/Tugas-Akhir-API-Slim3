<?php namespace AppExceptions;  
class Handler implements IlluminateContractsDebugExceptionHandler {  
  public function report(Exception $e) {
    throw $e;
  }

  public function render($request, Exception $e) {
    throw $e;
  }

  public function renderForConsole($output, Exception $e) {
    throw $e;
  }
}