PROJECT TITLE: Bandwidth Monitoring Tool with SNMP          PROJECT BY : ESHANK GUPTA , RAJ BANKA (SBU-RANCHI)                   PROJECT GUIDE: MR SHUBH MISHRA (STPI-RANCHI)

PROJECT DESCRIPTION :This project aims to replicate the functionality of PRTG (Paessler Router Traffic Grapher) for monitoring bandwidth details using SNMP (Simple Network Management Protocol). 
The tool collects SNMP data from network devices to generate real-time graphs and statistics on bandwidth usage. 

KEY FEATURES:
     :SNMP Integration: Utilizes SNMP to gather bandwidth data from supported network devices.
     :Graphical Representation: Generates intuitive graphs to visualize bandwidth usage over time.
     :Customizable Monitoring: Allows users to configure monitoring parameters and thresholds.
     :Alerting Mechanism: Notifies administrators of threshold breaches or network anomalies.
     :Scalable Architecture: Designed to handle large-scale network environments effectively.
NOTE:This project is ideal for network administrators and IT professionals seeking a lightweight, open-source alternative for bandwidth monitoring and analysis.

INSTALLATION INSTRUCTIONS:
 To install and set up our SNMP bandwidth monitoring tool using PHP, MySQL, and XAMPP, follow these step-by-step instructions:

#### 1. Install XAMPP

1. **Download XAMPP**: Visit the [XAMPP website](https://www.apachefriends.org/index.html) and download the installer for your operating system (Windows, macOS, Linux).
   
2. **Run the Installer**: Execute the installer and follow the on-screen instructions. Choose components such as Apache, MySQL, PHP, and phpMyAdmin during installation.

3. **Start Apache and MySQL**: After installation, start Apache and MySQL services using the XAMPP Control Panel.

#### 2. Set Up MySQL Database

1. **Access phpMyAdmin**: Open your web browser and go to `http://localhost/phpmyadmin`.

2. **Create Database**: Click on the "Databases" tab and create a new database for your project (e.g., `bandwidth_monitoring`).

3. **Create Tables**: Within your database, create tables as needed for storing SNMP data, configurations, and any other relevant information for your project. You can use the SQL tab in phpMyAdmin for this purpose.

#### 3. Configure PHP

1. **Edit PHP Configuration (php.ini)**:
   - Locate your `php.ini` file (usually found in `xampp\php\php.ini`).
   - Ensure that SNMP extension is enabled by uncommenting or adding the following line if it's not already enabled:

2. **Restart Apache**: Save your changes to `php.ini` and restart the Apache server via XAMPP Control Panel.

#### 4. Enable the SNMP Extension

 1.**Open the php.ini file located in your PHP installation directory.**
     Find the following line and remove the semicolon (;) at the beginning to enable the SNMP extension:
       ;extension=snmp
     Change it to:
       extension=snmp
 2.**Restart your web server (e.g., Apache, Nginx, or IIS) to apply the changes.**

#### 5. Download and Set Up our Project

1. **Clone or Download Project**: Clone your bandwidth monitoring tool project repository from GitHub or download the project files as a ZIP archive.

2. **Move Project Files**: Extract the project files into the `htdocs` directory of your XAMPP installation. This directory is usually located at `C:\xampp\htdocs` on Windows or `/Applications/XAMPP/htdocs` on macOS.

3. **Configure Database Connection**: 
   - Locate the configuration file where database connection details are defined (e.g., `config.php`, `database.php`, etc.).
   - Update the database connection settings (hostname, username, password, database name) to match your MySQL configuration.

#### 6. Initialize and Test

1. **Import Database Structure**: If your project includes SQL dump files (`*.sql`), import them into your database using phpMyAdmin to create necessary tables and initial data.

2. **Run the Application**: Open your web browser and navigate to `http://localhost/your_project_directory` to access your SNMP bandwidth monitoring tool.

3. **Test**: Verify that the application is working correctly by navigating through its functionalities, such as viewing SNMP graphs, setting up devices for monitoring, and configuring alerts.

#### 7. Additional Tips:

- **Security**: Ensure that your MySQL database and XAMPP server are properly secured. Use strong passwords and avoid exposing sensitive information in your configuration files.

- **Documentation**: Document any additional setup steps specific to your project, such as configuring SNMP agents on network devices or setting up cron jobs for automated data collection.
 
#### 8. FOLDERS & FILES:
    
  SNMP-Switch_Monitor(Main Folder):It includes various files which are mentioned below:
  
     1) index.php: Entry point for the Snmp Bandwidth Monitoring Tool i.e includes LOGIN PAGE (Sign-In & Sign-Up) with other social media loging credientials.
     2) login_page.css: Includes CSS file containing styles for the LOGIN PAGE of the  SNMP Application.
     3) login_page.js: This JavaScript file controls the behavior and functionality of the LOGIN PAGE within the Application and 
                       it handles user interactions related to logging in, authentication, and possibly session management.
     4) login_backend.php: This PHP script serves as the backend handler for processing Login requests from the frontend (e.g., login page JavaScript) and 
                           it verifies user credentials, performs authentication against a database or external service, and manages user sessions upon successful login.
     5) homepage.php: This PHP script serves the MONITORING DASHBOARD comprising the 3 Swicthes i.e NEW Building , OLD Noc & NEW Noc .
     6) homepage1.css: Includes CSS file containing styles for homepage.php for Monitoring Dashboard.
     6) interface.css: Includes CSS file containing styles for the INTERFACE DETAILS of each switches.
     7) Front_page.php: This PHP script serves the purpose of adding new network interfaces or devices to your monitoring system and 
                        it provides a form or interface for users to input details about the device or interface, which is then stored in a database or used to configure SNMP monitoring.
     8) display_switch_detail.php: This PHP script is responsible for displaying detailed information about network switches or devices within your monitoring system .
                                   It retrieves and presents specific details such as switch name, IP address, interface statuses, and possibly performance metrics.
     9) fetch_switch_detail.php: This PHP script is responsible for fetching details about network switches or devices from SNMP-enabled devices and 
                                 it typically interacts with SNMP agents(OIDS) on switches to retrieve specific information such as interface statuses, traffic statistics, and device configuration.
    10) display_bandwidth_graph.php: Fetches bandwidth data from network devices using SNMP, allowing for real-time monitoring and
                                     Generates clear and intuitive graphs that illustrate bandwidth usage trends over customizable time intervals.
    11) fetch_bandwidth_detail.php: Communicates with SNMP-enabled devices to gather bandwidth usage data and Processes the raw SNMP data into a structured format suitable for graphing and analysis. 
                                    It  is he processed data is output in a format that can be easily consumed by other scripts or components, such as JSON .
    12) connect.php: The connect.php script is a foundational component of our SNMP Bandwidth Monitoring Tool. This PHP file is responsible for establishing connections to SNMP-enabled network devices.
                     It handles the initialization of SNMP sessions and provides utility functions for querying the devices.
    13) snmperror.log: The snmperror.log file is a dedicated log file for recording SNMP-related errors encountered by the SNMP Bandwidth Monitoring Tool. 
                       This log file helps in troubleshooting and diagnosing issues related to SNMP operations within the tool.
     
  **Images directory** :Consists various Images of STPI
    jaipur1,jaipur2,jaipur3,jaipur4,jaipur5,foundation,found2,found3,found4,found5,found6,found7,found8,found9,found10,found11,found12,found13,undraw_laravel and undraw_maker.















