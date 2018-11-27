# Getting Started

## Required Software

- [Node.js v8+](https://nodejs.org/en/download/)
- [Gulp CLI v2+](https://gulpjs.com/)
- [Git v2.7+](https://git-scm.com/)
- [OpenSSH Client](https://help.ubuntu.com/lts/serverguide/openssh-server.html)

## Recommended Software

- [Atom](https://atom.io/)
- [Hyper](https://hyper.is/)
- [WSL](https://docs.microsoft.com/en-us/windows/wsl/install-win10) (Windows 10)
- [Node Version Manager](https://github.com/creationix/nvm#installation) (Mac, Linux, WSL)
- [LAMP](https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-16-04) (Mac, Linux, WSL)
- [libnotify-bin](https://packages.ubuntu.com/xenial/libnotify-bin) (Linux, WSL)
- [BurntToast](https://github.com/Windos/BurntToast) (WSL)

## Installation

This guide assumes that you're running Bash on Ubuntu 16.04; instructions may differ for Mac & Windows. If running Windows 10, it is highly recommend that you first follow [Microsoft's guide to enable the Windows Subsystem for Linux](https://docs.microsoft.com/en-us/windows/wsl/install-win10) for optimal compatibility.

### Step 1: Install Node.js

1. Open Terminal.

2. Run the Node Version Manager installation script ([latest version](https://github.com/creationix/nvm#install-script)).

    ```sh
    curl -o- https://raw.githubusercontent.com/creationix/nvm/v0.33.11/install.sh | bash
    ```

3. Verify that NVM was successfully installed by running `command -v nvm`. If `nvm: command not found` is returned, or otherwise no version information is returned, close out of all terminal windows and start a new session.

4. Install the latest LTS version of Node.js.

    ```sh
    nvm install --lts
    ```

5. Verify that Node.js was successfully installed by running `node -v`. If `node: command not found` is returned, or otherwise no version information is returned, close out of all terminal windows and start a new session.

### Step 2: Install the Gulp command line interface

1. Open Terminal.

2. Install the latest version of Gulp CLI.

    ```sh
    npm install --global gulp-cli
    ```

3. Verify that gulp-cli was successfully installed by running `gulp -v`. If `gulp: command not found` is returned, or otherwise no version information is returned, close out of all terminal windows and start a new session.

### Step 3: Install Git

1. Open Terminal.

2. Install the latest version of Git.

    ```sh
    sudo apt-get install git
    ```

3. Verify that git was successfully installed by running `git --version`. If `git: command not found` is returned, or otherwise no version information is returned, close out of all terminal windows and start a new session.

4. Set a Git username.

    ```sh
    git config --global user.name "Your Name"
    ```

5. Set an email address in Git. You can use your [GitHub-provided no-reply email address](https://help.github.com/articles/about-commit-email-addresses) or any email address.

    ```sh
    git config --global user.email "your_email@example.com"
    ```

### Step 4: Set up SSH

1. Open Terminal.

2. Install the latest version of OpenSSH Client.

    ```sh
    sudo apt-get install openssh-client
    ```

3. Verify that OpenSSH client was successfully installed by running `ssh --version`. If `ssh: command not found` is returned, or otherwise no version information is returned, close out of all terminal windows and start a new session.

4. Generate a new SSH key.

    ```sh
    ssh-keygen -t rsa -b 4096 -C "your_email@example.com"
    ```

5. Add the new SSH key to the SSH Agent.

    ```sh
    eval "$(ssh-agent -s)"
    ```

6. Print the public SSH key to your terminal with `cat ~/.ssh/id_rsa.pub`, and copy the output.

7. On [GitHub.com](https://github.com/), in the upper-right corner of any page, click your profile photo, then click **Settings**.

8. In the user settings sidebar, click **SSH and GPG keys**.

9. Click **New SSH key or Add SSH key**.

10. In the "title" field, add a descriptive label for the new key. For example, if you're using a personal Mac, you might call this key "Personal MacBook Air".

11. Paste your key into the "Key" field.

12. Click **Add SSH key**.

13. If prompted, confirm your GitHub password.

### Step 5: Clone the Repository

1. Navigate to the main page of the repository.

2. Under the repository name, click **Clone or download**.

3. In the Clone with SSH section, click the clipboard icon to copy the clone URL for the repository.

4. Open Terminal.

5. Change the current working directory to the location where you want the cloned directory to be made.

    ```sh
    cd ~/Repositories
    ```

6. Type `git clone`, and then pate the URL you copied in Step 2.

7. Press **Enter**. Your local clone will be created.

### Step 6: Install Node Modules

1. Open Terminal.

2. Change the current working directory to the location where you cloned the repository.

    ```sh
    cd ~/Repositories/__gulp_init_npm_name__
    ```

2. Install the required Node modules.

    ```sh
    npm install
    ```

3. Verify that the Node modules installed correctly by running `gulp`. If successful, a message `[12:00:00] gulp-notify: [Success!] 4 tasks complete! [scripts, styles, html, media]` should be displayed, and a new folder `dev` should appear in the repository folder.

## Setting up a Local Environment

A local environment may be not be needed, depending on what you're trying to do with this project. In many cases, it's easiest to simply upload to the server to test changes. If, however, you'd prefer to set up a local LAMP install, you can do so with this guide.

### Install Apache

1. Update the apt repository.

    ```sh
    sudo apt-get update
    ```

2. Install `apache2`.

    ```sh
    sudo apt-get install apache2 -y
    ```

3. Enable `mod_rewrite`.

    ```sh
    sudo a2enmod rewrite
    ```

3. Restart `apache2`.

    ```sh
    sudo service apache2 restart
    ```

4. Visit http://localhost/. A page titled "Apache2 Ubuntu Default Page" should appear.

### Install MySQL

1. Update the apt repository.

    ```sh
    sudo apt-get update
    ```

2. Install `mysql-server`.

    ```sh
    sudo apt-get install mysql-server -y
    ```

3. Start `mysql-server`.

    ```sh
    sudo service mysql start
    ```

4. Connect to MySQL. You should be greeted with `mysql>`.

    ```sh
    sudo mysql -u root -p
    ```

5. Disconnect from MySQL.

    ```sql
    exit;
    ```

### Install PHP

1. Update the apt repository.

    ```sh
    sudo apt-get update
    ```

2. Install PHP and some related extensions.

    ```sh
    sudo apt-get install php php-mysql libapache2-mod-php php-cli php-cgi php-gd
    ```

3. Create a test PHP file.

    ```sh
    sudo nano /var/www/html/index.php
    ```

4. Add a call to `phpinfo()` to the file.

    ```php
    <?php phpinfo(); ?>
    ```

5. Visit http://localhost/index.php. You should be greeted with "PHP Version..."

### Create a new database for WordPress

1. Connect to MySQL.

    ```sh
    sudo mysql -u root -p
    ```

2. Create a new database.

    ```sql
    create database 'database_name';
    ```

3. Create a new user, grant them permissions on the new database, and flush the privileges.

    ```sql
    CREATE USER 'your_username'@'localhost' IDENTIFIED BY 'your_password';
    GRANT ALL PRIVILEGES ON database_name.* TO 'your_username'@'localhost';
    FLUSH PRIVILEGES;
    ```

3. Exit MySQL.

    ```sql
    exit;
    ```

### Install WordPress

1. Download the latest version of WordPress.

    ```sh
    wget -c http://wordpress.org/latest.tar.gz
    ```

2. Extract WordPress.

    ```sh
    tar -xzvf latest.tar.gz
    ```

3. Move WordPress to your server directory.

    ```sh
    sudo rsync -av wordpress/* /var/www/html/
    ```

4. Correct permissions on your server directory.

    ```sh
    sudo chown -R www-data:www-data /var/www/html/
    sudo chmod -R 755 /var/www/html/
    ```

5. Visit http://localhost/wp-admin/setup-config.php and follow the prompts for installation.

### Create a Symbolic Link to the Theme

1. Navigate to the themes directory.

    ```sh
    cd /var/www/html/wp-content/themes
    ```

2. Create a new symbolic link from the dev folder to the themes directory.

    ```sh
    sudo ln -s ~/Repositories/__gulp_init_npm_name__/dev __gulp_init_npm_name__
    ```

### Activate the theme in WordPress

1. Log in to the dashboard at http://localhost/wp-admin/.

2. Navigate to Appearance > Themes.

3. Click "Activate" under "__gulp_init_full_name__."

## Enable Toast Notifications

When a gulp task completes, a notification can appear indicating which tasks have been ran. This should work automatically on Mac and Windows, but Linux and WSL may require installing [libnotify-bin](https://packages.ubuntu.com/xenial/libnotify-bin), and WSL will require installing [BurntToast](https://github.com/Windos/BurntToast).

### BurntToast

BurntToast is a PowerShell module that enables sending toast notifications from the command line. This project can utilize BurntToast as a bridge for toast notifications from WSL to Windows. To install and enable BurntToast, take the following steps:

1. Download the [latest version of BurntToast](https://github.com/Windos/BurntToast/releases) ([v0.6.2](https://github.com/Windos/BurntToast/releases/download/v0.6.2/BurntToast.zip) at time of writing).

2. Right click on BurntToast.zip, and select "Properties." Check the box next to "Unblock" to enable the module to execute.

3. Extra BurntToast to `C:\Users\[User]\Documents\WindowsPowerShell\modules\BurntToast` (you may have to create these directories if they don't exist).

4. If your execution policy is set to `Restricted`, `AllSigned`, or `Undefined`, you'll need to change it to at least `RemoteSigned` in order to execute BurntToast. Open an elevated PowerShell window, and adjust your execution policy.

      ```ps
      Set-ExecutionPolicy -ExecutionPolicy RemoteSigned
      ```

5. In an eleveated PowerShell window, import the BurntToast module.

      ```ps
      Import-Module BurntToast
      ```

6. Verify that BurntToast is working correctly. You should receive a toast notification labeled "Default Notification."

      ```ps
      New-BurntToastNotification
      ```

7. In order for this repository to use BurntToast, a variable needs defined in `.config/.env`. If this file doesn't exist, you may need to create it. In `.config/.env`, set `BURNTTOAST=true`.

      ```
      BURNTTOAST=true
      ```

8. In WSL, browse to this repository and run `rm -rf dev; gulp` to verify that BurntToast is sending notifications.
