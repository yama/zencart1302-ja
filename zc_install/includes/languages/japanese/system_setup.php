<?php
/**
 * @package Installer
 * @access private
 * @copyright Copyright 2003-2005 Zen Cart Development Team
 * @copyright Portions Copyright 2003 osCommerce
 * @license http://www.zen-cart.com/license/2_0.txt GNU Public License V2.0
 * @version $Id: system_setup.php 2342 2005-11-13 01:07:55Z drbyte $
 */
/**
 * defining language components for the page
 */
  define('SAVE_SYSTEM_SETTINGS', '設定を保存'); //this comes before TEXT_MAIN
  define('TEXT_MAIN', 'システム環境を設定します。確認の上、「設定を保存」をクリックしてください。<br />※各々の値において、<b>末尾にスラッシュ「/」をつけない</b>ようにしてください。');
  define('TEXT_PAGE_HEADING', 'Zen Cartの設定　- システム設定');
  define('SERVER_SETTINGS', 'サーバ設定');
  define('PHYSICAL_PATH', '設置ディレクトリ');
  define('PHYSICAL_PATH_INSTRUCTION', '設置ディレクトリの物理パス');
  define('PHYSICAL_HTTPS_PATH', '設置ディレクトリ(SSL/不要なら空白)');
  define('PHYSICAL_HTTPS_PATH_INSTRUCTION', 'SSL領域にインストールしたZen Cartの設置ディレクトリの物理パス');
  define('VIRTUAL_HTTP_PATH', 'サイトのURL');
  define('VIRTUAL_HTTP_PATH_INSTRUCTION', '');
  define('VIRTUAL_HTTPS_PATH', 'サイトのURL(SSL/不要なら空白)');
  define('VIRTUAL_HTTPS_PATH_INSTRUCTION', '');
  define('VIRTUAL_HTTPS_SERVER', 'SSLサーバのホスト名(不要なら空白)');
  define('VIRTUAL_HTTPS_SERVER_INSTRUCTION', 'Zen Cartディレクトリ用仮想HTTPSサーバ');
  define('ENABLE_SSL', 'ショップでSSLを有効にする');
  define('ENABLE_SSL_INSTRUCTION', 'SSLの動作を確認していない場合は「いいえ」にしておいてください。<br />');
  define('ENABLE_SSL_ADMIN', '管理画面でSSLを有効にする');
  define('ENABLE_SSL_ADMIN_INSTRUCTION', 'SSLの動作を確認していない場合は「いいえ」にしておいてください。<br />');
