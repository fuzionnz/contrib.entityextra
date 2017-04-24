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
    if (strtolower($apiRequest['action'] == 'get')) {
      switch (strtolower($apiRequest['entity'])) {
        case 'contributionpage':
          if (isset($result['values'])) {
            foreach ($result['values'] as &$contributionPage) {
              if (!isset($contributionPage['price_set'])) {
                // Returns a single integer or false.
                if ($priceSetId = CRM_Price_BAO_PriceSet::getFor('civicrm_contribution_page', $contributionPage['id'])) {
                  $priceSetResult = civicrm_api3('PriceSet', 'get', ['id' => $priceSetId]);
                  if (!empty($priceSetResult['values'])) {
                    $price_set = $priceSetResult['values'];
                    $priceFieldResult = civicrm_api3('PriceField', 'get', ['price_set_id' => $priceSetId]);
                    if (!empty($priceFieldResult['values'])) {
                      $price_set[$priceSetId]['price_field'] = $priceFieldResult['values'];
                      foreach ($priceFieldResult['values'] as $k => $priceField) {
                        $price_set[$priceSetId]['price_field'][$k] = $priceField;
                        $pfv_res = civicrm_api3('PriceFieldValue', 'get', ['price_field_id' => $k]);
                        if (!empty($pfv_res['values'])) {
                          $price_set[$priceSetId]['price_field'][$k]['values'] = $pfv_res;
                        }
                      }
                    }
                  }
                  $result['values']['price_set'] = $price_set;
                }
              }
            }
          }

      }
    }
    return $result;
  }
}