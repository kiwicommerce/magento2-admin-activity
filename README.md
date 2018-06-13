## Magento 2 - Admin Activity by KiwiCommerce

### Overview
- Easily track every admin activity like add, edit, delete, print, view, mass update etc.
- Failed attempts of admin login are recorded as well. You get access to the user’s login information and IP address.
- Track page visit history of admin.
- Track fields that have been changed from the backend.
- Allow administrator to revert back the modification.

### **Installation**
 
 1. Composer Installation
      - Navigate to your Magento root folder<br />
            `cd path_to_the_magento_root_directory`<br />
      - Then run the following command<br />
          `composer require kiwicommerce/module-admin-activity`<br/>
      - Make sure that composer finished the installation without errors.

 2. Command Line Installation
      - Backup your web directory and database.
      - Download Admin Activity Log installation package from <a href="https://github.com/kiwicommerce/magento2-admin-activity/releases/download/v1.0.3/kiwicommerce-admin-activity-v103.zip">here</a>.
      - Upload contents of the Admin Activity Log installation package to your Magento root directory.
      - Navigate to your Magento root folder<br />
          `cd path_to_the_magento_root_directory`<br />
      - Then run the following command<br />
          `php bin/magento module:enable KiwiCommerce_AdminActivity`<br />
      - Log out from the backend and log in again.
   
- After install the extension, run the following command <br/>
          `php bin/magento setup:upgrade`<br />
          `php bin/magento setup:di:compile`<br />
          `php bin/magento setup:static-content:deploy`<br />
          `php bin/magento cache:flush`

Find More details on <a href="https://kiwicommerce.co.uk/extensions/magento2-admin-activity/" target="_blank">KiwiCommerce</a>

## Where will it appear in the Admin Panel

### Admin Activity Log

Go to **System > Admin Activity by KiwiCommerce > Admin Activity**. Here you can See the list of admin activity logs and page visit history.

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/06/admin-activity-history.png"/><br/>

- Page Visit History

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/06/page-visit-history.png"/><br/>

By clicking View in each admin activity log, you can see the slider with admin activity log details.

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/05/activity-log-slider.png"/> <br/>

### Login Activity

Go to **System > Admin Activity by KiwiCommerce > Login Activity**. Here you can See the list of login activity logs.

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/06/admin-activity-history.png"/><br/>

### Configuration

You need to follow this path. **System > Admin Activity by KiwiCommerce > Configuration**
- General configuration

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/05/configuration-general-section.png" /> <br/>

- Allow Module Section

<img src="https://kiwicommerce.co.uk/wp-content/uploads/2018/05/configuration-allow-module-section.png" /> <br/>

## Contribution
Well unfortunately there is no formal way to contribute, we would encourage you to feel free and contribute by:
 
  - Creating bug reports, issues or feature requests on <a target="_blank" href="https://github.com/kiwicommerce/magento2-admin-activity/issues">Github</a>
  - Submitting pull requests for improvements.
    
We love answering questions or doubts simply ask us in issue section. We're looking forward to hearing from you!
 
  - Follow us <a href="https://twitter.com/KiwiCommerce">@KiwiCommerce</a>
  - <a href="mailto:support@kiwicommerce.co.uk">Email Us</a>
  - Have a look at our <a href="https://kiwicommerce.co.uk/docs/admin-activity/">documentation</a> 

