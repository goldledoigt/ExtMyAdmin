<?php

/**
 * ExtMyAdmin settings
 *
 * @class settings
 */
abstract class settings {
  /**
   * Database hostname
   *
   * @property-read {string} db_host
   */
  const db_host = 'localhost';

  /**
   * Database username
   *
   * @property-read {string} db_user
   */
  const db_user = 'user';

  /**
   * Database password
   *
   * @property-read {string} db_pass
   */
  const db_pass = 'passwd';

  /**
   * ORM class
   *
   * @property-read {orm} orm_class
   */
  const orm_class = 'mysql';
}
