<?php
/**
 * create_account header_php.php
 *
 * @package page
 * @copyright Copyright 2003-2006 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: create_account.php 3777 2006-06-15 07:03:03Z drbyte $
 */
if (!defined('IS_ADMIN_FLAG')) {
  die('Illegal Access');
}

/**
 * Set some defaults
 */
  if (ACCOUNT_STATE == 'true' && ACCOUNT_STATE_DRAW_INITIAL_DROPDOWN == 'true') {
    $zone_id = 0;
    $check_query = "select count(*) as total
                      from " . TABLE_ZONES . "
                      where zone_country_id = '" . (int)SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY . "'";
    $check = $db->Execute($check_query);
    $entry_state_has_zones = ($check->fields['total'] > 0);
    if ($entry_state_has_zones == true) {
      $zones_array = array();
      $zones_array[] = array('id' => PULL_DOWN_ALL, 'text' => PULL_DOWN_ALL);
      $zones_values = $db->Execute("select zone_name
                                   from " . TABLE_ZONES . "
                                   where zone_country_id = '" . (int)SHOW_CREATE_ACCOUNT_DEFAULT_COUNTRY . "'
                                   order by zone_id");
      while (!$zones_values->EOF) {
        $zones_array[] = array('id' => $zones_values->fields['zone_name'], 'text' => $zones_values->fields['zone_name']);
        $zones_values->MoveNext();
      }
    }
  }


  $process = false;

/**
 * Process form contents
 */
if (isset($_POST['action']) && ($_POST['action'] == 'process')) {
  $process = true;

  if (ACCOUNT_GENDER == 'true') {
    if (isset($_POST['gender'])) {
      $gender = zen_db_prepare_input($_POST['gender']);
    } else {
      $gender = false;
    }
  }

  if (isset($_POST['email_format'])) {
    $email_format = zen_db_prepare_input($_POST['email_format']);
  } else {
    $email_format = false;
  }


  $firstname = zen_db_prepare_input($_POST['firstname']);
  $lastname = zen_db_prepare_input($_POST['lastname']);
  // ->furikana
  if (FURIKANA_NESESSARY) {
    $firstname_kana = zen_db_prepare_input($_POST['firstname_kana']);
    $lastname_kana = zen_db_prepare_input($_POST['lastname_kana']);
  }
  // <-furikana
  $nick = zen_db_prepare_input($_POST['nick']);
  //    if (ACCOUNT_DOB == 'true') $dob = zen_db_prepare_input($_POST['dob']);
  if (ACCOUNT_DOB == 'true') $dob = (empty($_POST['dob']) ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_db_prepare_input($_POST['dob']));

  $email_address = zen_db_prepare_input($_POST['email_address']);
  if (ACCOUNT_COMPANY == 'true') $company = zen_db_prepare_input($_POST['company']);
  $street_address = zen_db_prepare_input($_POST['street_address']);
  if (ACCOUNT_SUBURB == 'true') $suburb = zen_db_prepare_input($_POST['suburb']);
  $postcode = zen_db_prepare_input($_POST['postcode']);
  $city = zen_db_prepare_input($_POST['city']);
  if (ACCOUNT_STATE == 'true') {
    $state = zen_db_prepare_input($_POST['state']);
    if (isset($_POST['zone_id'])) {
      $zone_id = zen_db_prepare_input($_POST['zone_id']);
    } else {
      $zone_id = false;
    }
  }
  $country = zen_db_prepare_input($_POST['country']);
  $telephone = zen_db_prepare_input($_POST['telephone']);
  $fax = zen_db_prepare_input($_POST['fax']);
  $email_format = zen_db_prepare_input($_POST['email_format']);
  $customers_authorization = CUSTOMERS_APPROVAL_AUTHORIZATION;
  $customers_referral = zen_db_prepare_input($_POST['customers_referral']);

  if (isset($_POST['newsletter'])) {
    $newsletter = zen_db_prepare_input($_POST['newsletter']);
  } else {
    $newsletter = false;
  }
  $password = zen_db_prepare_input($_POST['password']);
  $confirmation = zen_db_prepare_input($_POST['confirmation']);

  $error = false;

  if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
    if (!isset($_POST['privacy_conditions']) || ($_POST['privacy_conditions'] != '1')) {
      $error = true;
      $messageStack->add('create_account', ERROR_PRIVACY_STATEMENT_NOT_ACCEPTED, 'error');
    }
  }

  if (ACCOUNT_GENDER == 'true') {
    if ( ($gender != 'm') && ($gender != 'f') ) {
      $error = true;

      $messageStack->add('create_account', ENTRY_GENDER_ERROR);
    }
  }

  if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_FIRST_NAME_ERROR);
  }

  if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_LAST_NAME_ERROR);
  }

  // ->furikana
  if (FURIKANA_NESESSARY) {
    if (strlen($firstname_kana) < ENTRY_FIRST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_FIRST_NAME_KANA_ERROR);
    }

    if (strlen($lastname_kana) < ENTRY_LAST_NAME_MIN_LENGTH) {
      $error = true;

      $messageStack->add('create_account', ENTRY_LAST_NAME_KANA_ERROR);
    }
  }
  // <-furikana

  if (ACCOUNT_DOB == 'true') {
    if (ENTRY_DOB_MIN_LENGTH > 0 or !empty($_POST['dob'])) {
      if (substr_count($dob,'/') > 2 || checkdate((int)substr(zen_date_raw($dob), 4, 2), (int)substr(zen_date_raw($dob), 6, 2), (int)substr(zen_date_raw($dob), 0, 4)) == false) {
        $error = true;

        $messageStack->add('create_account', ENTRY_DATE_OF_BIRTH_ERROR);
      }
    }
  }

  if (strlen($email_address) < ENTRY_EMAIL_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR);
  } elseif (zen_validate_email($email_address) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_CHECK_ERROR);
  } else {
    $check_email_query = "select count(*) as total
                            from " . TABLE_CUSTOMERS . "
                            where customers_email_address = '" . zen_db_input($email_address) . "'";

    $check_email = $db->Execute($check_email_query);

    if ($check_email->fields['total'] > 0) {
      $error = true;

      $messageStack->add('create_account', ENTRY_EMAIL_ADDRESS_ERROR_EXISTS);
    }
  }

  if ($phpBB->phpBB['installed'] == true) {
    if (strlen($nick) < ENTRY_NICK_MIN_LENGTH)  {
      $error = true;
      $messageStack->add('create_account', ENTRY_NICK_LENGTH_ERROR);
    } else {
      // check Zen Cart for duplicate nickname
      $check_nick_query = "select * from " . TABLE_CUSTOMERS  . "
                           where customers_nick = '" . $nick . "'";
      $check_nick = $db->Execute($check_nick_query);
      if ($check_nick->RecordCount() > 0 ) {
        $error = true;
        $messageStack->add('create_account', ENTRY_NICK_DUPLICATE_ERROR);
      }
      // check phpBB for duplicate nickname
      if ($phpBB->phpbb_check_for_duplicate_nick($nick) == 'already_exists' ) {
        $error = true;
        $messageStack->add('create_account', ENTRY_NICK_DUPLICATE_ERROR . '(phpBB)');
      }
    }
  }


  if (strlen($street_address) < ENTRY_STREET_ADDRESS_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_STREET_ADDRESS_ERROR);
  }

  if (strlen($postcode) < ENTRY_POSTCODE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_POST_CODE_ERROR);
  }

  if (strlen($city) < ENTRY_CITY_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_CITY_ERROR);
  }

  if (is_numeric($country) == false) {
    $error = true;

    $messageStack->add('create_account', ENTRY_COUNTRY_ERROR);
  }

  if (ACCOUNT_STATE == 'true') {
    $zone_id = 0;
    $check_query = "select count(*) as total
                      from " . TABLE_ZONES . "
                      where zone_country_id = '" . (int)$country . "'";

    $check = $db->Execute($check_query);

    $entry_state_has_zones = ($check->fields['total'] > 0);
    if ($entry_state_has_zones == true) {
      $zones_array = array();
      $zones_array[] = array('id' => PULL_DOWN_ALL, 'text' => PULL_DOWN_ALL);
      $zones_values = $db->Execute("select zone_name
                                   from " . TABLE_ZONES . "
                                   where zone_country_id = '" . (int)$country . "'
                                   order by zone_id");

      while (!$zones_values->EOF) {
        $zones_array[] = array('id' => $zones_values->fields['zone_name'], 'text' => $zones_values->fields['zone_name']);
        $zones_values->MoveNext();
      }
      $zone_query = "select distinct zone_id, zone_name
                       from " . TABLE_ZONES . "
                       where zone_country_id = '" . (int)$country . "'
                       and zone_code =  '" . strtoupper(zen_db_input($state)) . "'";

      $zone = $db->Execute($zone_query);
      if ($zone->RecordCount() > 0) {
        $zone_id = $zone->fields['zone_id'];
        $zone_name = $zone->fields['zone_name'];

      } else {

        $zone_query = "select distinct zone_id, zone_name
                         from " . TABLE_ZONES . "
                         where zone_country_id = '" . (int)$country . "'
                         and (zone_name like '" . zen_db_input($state) . "'
                         or zone_code like '" . zen_db_input($state) . "')";

        $zone = $db->Execute($zone_query);

        if ($zone->RecordCount() > 0) {
          $zone_id = $zone->fields['zone_id'];
          $zone_name = $zone->fields['zone_name'];
        }
      }
      if (!$zone_name) {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR_SELECT);
      }
    } else {
      if (strlen($state) < ENTRY_STATE_MIN_LENGTH) {
        $error = true;

        $messageStack->add('create_account', ENTRY_STATE_ERROR);
      }
    }
  }

  if (strlen($telephone) < ENTRY_TELEPHONE_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_TELEPHONE_NUMBER_ERROR);
  }


  if (strlen($password) < ENTRY_PASSWORD_MIN_LENGTH) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR);
  } elseif ($password != $confirmation) {
    $error = true;

    $messageStack->add('create_account', ENTRY_PASSWORD_ERROR_NOT_MATCHING);
  }

  if ($error == true) {
    // hook notifier class
    $zco_notifier->notify('NOTIFY_FAILURE_DURING_CREATE_ACCOUNT');
  } else {
    // ->furikana
    if (FURIKANA_NESESSARY) {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_firstname_kana' => $firstname_kana,
                              'customers_lastname_kana' => $lastname_kana,
                              'customers_email_address' => $email_address,
                              'customers_nick' => $nick,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => (int)$newsletter,
                              'customers_email_format' => $email_format,
                              'customers_default_address_id' => '0',
                              'customers_password' => zen_encrypt_password($password),
                              'customers_authorization' => (int)CUSTOMERS_APPROVAL_AUTHORIZATION
      );
    }
    else {
      $sql_data_array = array('customers_firstname' => $firstname,
                              'customers_lastname' => $lastname,
                              'customers_email_address' => $email_address,
                              'customers_nick' => $nick,
                              'customers_telephone' => $telephone,
                              'customers_fax' => $fax,
                              'customers_newsletter' => (int)$newsletter,
                              'customers_email_format' => $email_format,
                              'customers_default_address_id' => '0',
                              'customers_password' => zen_encrypt_password($password),
                              'customers_authorization' => (int)CUSTOMERS_APPROVAL_AUTHORIZATION
      );
    }
    // <-furikana

    if ((CUSTOMERS_REFERRAL_STATUS == '2' and $customers_referral != '')) $sql_data_array['customers_referral'] = $customers_referral;
    if (ACCOUNT_GENDER == 'true') $sql_data_array['customers_gender'] = $gender;
    //      if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = zen_date_raw($dob);
    if (ACCOUNT_DOB == 'true') $sql_data_array['customers_dob'] = (empty($_POST['dob']) ? zen_db_prepare_input('0001-01-01 00:00:00') : zen_date_raw($_POST['dob']));

    zen_db_perform(TABLE_CUSTOMERS, $sql_data_array);

    $_SESSION['customer_id'] = $db->Insert_ID();

    // ->furikana
    if (FURIKANA_NESESSARY) {
      $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_firstname_kana' => $firstname_kana,
                              'entry_lastname_kana' => $lastname_kana,
                              'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);
    }
    else {
      $sql_data_array = array('customers_id' => $_SESSION['customer_id'],
                              'entry_firstname' => $firstname,
                              'entry_lastname' => $lastname,
                              'entry_telephone' => $telephone,
                              'entry_fax' => $fax,
                              'entry_street_address' => $street_address,
                              'entry_postcode' => $postcode,
                              'entry_city' => $city,
                              'entry_country_id' => $country);
    }
    // <-furikana

    if (ACCOUNT_GENDER == 'true') $sql_data_array['entry_gender'] = $gender;
    if (ACCOUNT_COMPANY == 'true') $sql_data_array['entry_company'] = $company;
    if (ACCOUNT_SUBURB == 'true') $sql_data_array['entry_suburb'] = $suburb;
    if (ACCOUNT_STATE == 'true') {
      if ($zone_id > 0) {
        $sql_data_array['entry_zone_id'] = $zone_id;
        $sql_data_array['entry_state'] = '';
      } else {
        $sql_data_array['entry_zone_id'] = '0';
        $sql_data_array['entry_state'] = $state;
      }
    }

    zen_db_perform(TABLE_ADDRESS_BOOK, $sql_data_array);

    $address_id = $db->Insert_ID();

    $sql = "update " . TABLE_CUSTOMERS . "
              set customers_default_address_id = '" . (int)$address_id . "'
              where customers_id = '" . (int)$_SESSION['customer_id'] . "'";

    $db->Execute($sql);

    $sql = "insert into " . TABLE_CUSTOMERS_INFO . "
                          (customers_info_id, customers_info_number_of_logons,
                           customers_info_date_account_created)
              values ('" . (int)$_SESSION['customer_id'] . "', '0', now())";

    $db->Execute($sql);

    // phpBB create account
    if ($phpBB->phpBB['installed'] == true) {
      $phpBB->phpbb_create_account($nick, $password, $email_address);
    }
    // End phppBB create account

    if (SESSION_RECREATE == 'True') {
      zen_session_recreate();
    }

    $_SESSION['customer_first_name'] = $firstname;
    $_SESSION['customer_last_name'] = $lastname;
    // ->furikana
    if (FURIKANA_NESESSARY) {
      $_SESSION['customer_first_name_kana'] = $firstname_kana;
      $_SESSION['customer_last_name_kana'] = $lastname_kana;
    }
    // <-furikana
    $_SESSION['customer_default_address_id'] = $address_id;
    $_SESSION['customer_country_id'] = $country;
    $_SESSION['customer_zone_id'] = $zone_id;
    $_SESSION['customers_authorization'] = $customers_authorization;

    // restore cart contents
    $_SESSION['cart']->restore_contents();

    // hook notifier class
    $zco_notifier->notify('NOTIFY_LOGIN_SUCCESS_VIA_CREATE_ACCOUNT');

    // build the message content
    $name = $firstname . ' ' . $lastname;

    if (ACCOUNT_GENDER == 'true') {
      if ($gender == 'm') {
        $email_text = sprintf(EMAIL_GREET_MR, $name);
      } else {
        $email_text = sprintf(EMAIL_GREET_MS, $name);
      }
    } else {
      $email_text = sprintf(EMAIL_GREET_NONE, $name);
    }
    $html_msg['EMAIL_GREETING'] = str_replace('\n','',$email_text);
    $html_msg['EMAIL_FIRST_NAME'] = $firstname;
    $html_msg['EMAIL_LAST_NAME']  = $lastname;

    // initial welcome
    $email_text .=  EMAIL_WELCOME;
    $html_msg['EMAIL_WELCOME'] = str_replace('\n','',EMAIL_WELCOME);

    if (NEW_SIGNUP_DISCOUNT_COUPON != '' and NEW_SIGNUP_DISCOUNT_COUPON != '0') {
      $coupon_id = NEW_SIGNUP_DISCOUNT_COUPON;
      $coupon = $db->Execute("select * from " . TABLE_COUPONS . " where coupon_id = '" . $coupon_id . "'");
      $coupon_desc = $db->Execute("select coupon_description from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . $coupon_id . "' and language_id = '" . $_SESSION['languages_id'] . "'");
      $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $coupon_id ."', '0', 'Admin', '" . $email_address . "', now() )");

      // if on, add in Discount Coupon explanation
      //        $email_text .= EMAIL_COUPON_INCENTIVE_HEADER .
      $email_text .= "\n" . EMAIL_COUPON_INCENTIVE_HEADER .
      (!empty($coupon_desc->fields['coupon_description']) ? $coupon_desc->fields['coupon_description'] . "\n\n" : '') .
      strip_tags(sprintf(EMAIL_COUPON_REDEEM, ' ' . $coupon->fields['coupon_code'])) . EMAIL_SEPARATOR;

      $html_msg['COUPON_TEXT_VOUCHER_IS'] = EMAIL_COUPON_INCENTIVE_HEADER ;
      $html_msg['COUPON_DESCRIPTION']     = (!empty($coupon_desc->fields['coupon_description']) ? '<strong>' . $coupon_desc->fields['coupon_description'] . '</strong>' : '');
      $html_msg['COUPON_TEXT_TO_REDEEM']  = str_replace("\n", '', sprintf(EMAIL_COUPON_REDEEM, ''));
      $html_msg['COUPON_CODE']  = $coupon->fields['coupon_code'];
    } //endif coupon

    if (NEW_SIGNUP_GIFT_VOUCHER_AMOUNT > 0) {
      $coupon_code = zen_create_coupon_code();
      $insert_query = $db->Execute("insert into " . TABLE_COUPONS . " (coupon_code, coupon_type, coupon_amount, date_created) values ('" . $coupon_code . "', 'G', '" . NEW_SIGNUP_GIFT_VOUCHER_AMOUNT . "', now())");
      $insert_id = $db->Insert_ID();
      $db->Execute("insert into " . TABLE_COUPON_EMAIL_TRACK . " (coupon_id, customer_id_sent, sent_firstname, emailed_to, date_sent) values ('" . $insert_id ."', '0', 'Admin', '" . $email_address . "', now() )");

      // if on, add in GV explanation
      $email_text .= "\n\n" . sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) .
      sprintf(EMAIL_GV_REDEEM, $coupon_code) .
      EMAIL_GV_LINK . zen_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code, 'NONSSL', false) . "\n\n" .
      EMAIL_GV_LINK_OTHER . EMAIL_SEPARATOR;
      $html_msg['GV_WORTH'] = str_replace('\n','',sprintf(EMAIL_GV_INCENTIVE_HEADER, $currencies->format(NEW_SIGNUP_GIFT_VOUCHER_AMOUNT)) );
      $html_msg['GV_REDEEM'] = str_replace('\n','',str_replace('\n\n','<br />',sprintf(EMAIL_GV_REDEEM, '<strong>' . $coupon_code . '</strong>')));
      $html_msg['GV_CODE_NUM'] = $coupon_code;
      $html_msg['GV_CODE_URL'] = str_replace('\n','',EMAIL_GV_LINK . '<a href="' . zen_href_link(FILENAME_GV_REDEEM, 'gv_no=' . $coupon_code, 'NONSSL', false) . '">' . TEXT_GV_NAME . ': ' . $coupon_code . '</a>');
      $html_msg['GV_LINK_OTHER'] = EMAIL_GV_LINK_OTHER;
    } // endif voucher

    // add in regular email welcome text
    $email_text .= "\n\n" . EMAIL_TEXT . EMAIL_CONTACT . EMAIL_GV_CLOSURE;

    $html_msg['EMAIL_MESSAGE_HTML']  = str_replace('\n','',EMAIL_TEXT);
    $html_msg['EMAIL_CONTACT_OWNER'] = str_replace('\n','',EMAIL_CONTACT);
    $html_msg['EMAIL_CLOSURE']       = nl2br(EMAIL_GV_CLOSURE);

    // include create-account-specific disclaimer
    $email_text .= "\n\n" . sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, STORE_OWNER_EMAIL_ADDRESS). "\n\n";
    $html_msg['EMAIL_DISCLAIMER'] = sprintf(EMAIL_DISCLAIMER_NEW_CUSTOMER, '<a href="mailto:' . STORE_OWNER_EMAIL_ADDRESS . '">'. STORE_OWNER_EMAIL_ADDRESS .' </a>');

    // send welcome email
    zen_mail($name, $email_address, EMAIL_SUBJECT, $email_text, STORE_NAME, EMAIL_FROM, $html_msg, 'welcome');

    // send additional emails
    if (SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_STATUS == '1' and SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO !='') {
      if ($_SESSION['customer_id']) {
        $account_query = "select customers_firstname, customers_lastname, customers_email_address
                            from " . TABLE_CUSTOMERS . "
                            where customers_id = '" . (int)$_SESSION['customer_id'] . "'";

        $account = $db->Execute($account_query);
      }

      $extra_info=email_collect_extra_info($name,$email_address, $account->fields['customers_firstname'] . ' ' . $account->fields['customers_lastname'] , $account->fields['customers_email_address'] );
      $html_msg['EXTRA_INFO'] = $extra_info['HTML'];
      zen_mail('', SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO, SEND_EXTRA_CREATE_ACCOUNT_EMAILS_TO_SUBJECT . ' ' . EMAIL_SUBJECT,
      $email_text . $extra_info['TEXT'], STORE_NAME, EMAIL_FROM, $html_msg, 'welcome_extra');
    } //endif send extra emails

    zen_redirect(zen_href_link(FILENAME_CREATE_ACCOUNT_SUCCESS, '', 'SSL'));

  } //endif !error
}
?>
