<?php

class CRM_EntityExtra_APIWrapper implements API_Wrapper {
  /**
   * the wrapper contains a method that allows you to alter the parameters of the api request (including the action and the entity)
   */
  public function fromApiInput($apiRequest) {
    return $apiRequest;
  }

  /**
   * Alter the result before returning it to the caller.
   */
  public function toApiOutput($apiRequest, $result) {
    switch ($apiRequest['entity']) {
      case 'ContributionPage':
        error_log(print_r($apiRequest, 1));
        if (!isset($result['some_random_id'])) {
          $result['some_random_id'] = 'xxx';
          // $result['values'][$result['id']]['display_name_munged'] = 'MUNGE! ' . $result['values'][$result['id']]['display_name'];
          // unset($result['values'][$result['id']]['display_name']);
        }
    }
    return $result;
  }
}