CREATE TABLE  paypal_rp_product_profile  (
                id int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
                products_id int(11) NOT NULL DEFAULT 0,
                profile_start_date datetime DEFAULT NULL,
                billing_period enum('day','week','semimonth','month','year') DEFAULT NULL,
                billing_frequency smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                total_billing_cycles smallint(5) UNSIGNED NOT NULL DEFAULT 0,
                trial_profile_start_date datetime DEFAULT NULL,
                trial_billing_period enum('day','week','semimonth','month','year') DEFAULT NULL,
                trial_billing_frequency smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                trial_total_billing_cycles smallint(5) UNSIGNED NOT NULL DEFAULT 1,
                trial_amt decimal(15,4) NOT NULL,
                init_amt decimal(15,2) unsigned NOT NULL DEFAULT 0,
                failed_init_amt_action enum('ContinueOnFailure', 'CancelOnFailure') NOT NULL DEFAULT 'ContinueOnFailure',
                PRIMARY KEY (`id`),
                KEY `products_id` (`products_id`)
           );


CREATE TABLE paypal_rp_profile_status (
            orders_id int(11) NOT NULL,
              rp_profile_id varchar(20) NOT NULL,
              status varchar(30) NOT NULL,
              last_updated datetime NOT NULL,
              next_billing datetime NOT NULL,
              profile_start_date datetime NOT NULL,
              profile_last_payment datetime NOT NULL,
              PRIMARY KEY (`rp_profile_id`, `orders_id`)
            );
        
alter table products add products_type enum('standard', 'recurring') not null default 'standard';
