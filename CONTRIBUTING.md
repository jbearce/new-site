## Contributing

This project uses Gulp, a command line task runner, which can seem daunting at first, but I promise, it's worth it!

### Install Gulp

Before installing Gulp, you'll need Node JS.

1. Download Node JS from [nodejs.org](https://nodejs.org/en/).

2. Once the download finishes, run through the installer. All default settings will work fine.

3. Reboot your computer.

Now that Node JS is installed, you can install Gulp.

1. Open up a terminal window.

2. Type `npm install --global gulp` and press enter.

### Using Gulp

To use this project's Gulp tasks, you'll need to run `npm install` in the same folder that package.json exists to set up it's dependencies. 

1. Open a new command prompt window

2. Use the CD (change directory) command to to navigate to the appropraite folder. For me, I'd type `cd C:/Users/Jacob/Repositories/new-site`.

3. Once you've navigate to the correct directory, type `npm install` to set up the project.

4. Wait for all packages to finish installing. It can take a few minutes, so be patient. You'll know it's done when you're presented with a new line to type new commands in to.

Now that you've installed all the project dependencies, you're ready to start using Gulp! There are a few tasks that I've set up:

- `gulp watch` This tastk sets up a local server at [http://localhost:3000/](http://localhost:3000/), watches for file changes in `./src`, runs the default task `gulp`, and auto-updates the local server.

- `gulp styles` This task compiles all the SCSS and puts it in /dev/assets/styles/all.css, in expanded form.

- `gulp scripts` This task compiles all the scripts and puts it in /dev/assets/scripts/all.js, in expanded form.

- `gulp media` This task uses lossless compression to shrink image size, and puts them in /dev/assets/media/

- `gulp html` This task compiles all the HTML and puts it in /dev/, in expanded form.

- `gulp dist` This task minifies all styles, scripts, media, and HTML in /dev/, putting everything in /dist/

- `gulp build` This task runs clean, styles, scripts, media, html, and dist, putting everything in /dist/

- `gulp clean` This task deletes the /dev/ and /dist/ directories from your local machine. This task gets used by other tasks automatically, and you shouldn't need to use it manually.

- `gulp` This is the default task, and it runs styles, scripts, media, and html all at once, putting everything in /dev/
