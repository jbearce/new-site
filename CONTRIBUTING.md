## Getting Started

I'm using a few advanced things that you might need some help with. Here's a quick start guide, just in case.

#### Step 1: Cloning the Repository

First off, you're going to need to clone this repository. If you've never used Git before, I recommend taking a look at [this article form A List Apart](alistapart.com/article/get-started-with-git). It gives a great overview of the basics of Git.

Once you've got Git set up on your system, just follow these steps to set up this project:

 1. Open up a terminal window where you want the project to reside.
 2. Attempt to set up SSH using [this guide](http://git.weblinx.local/weblinx/git-sandbox/blob/master/ssh.md). If successful, move to step 5. If unsuccessful, move to setp 4.
 3. Type `git clone git@git.ghostlyco.de:revxx14/new-site.git`
 4. Type `git clone http://username:password@git.ghostlyco.de/revxx14/new-site.git`, where `username` is your GitLab username and `password` is your password. For example, I'd use: `git clone http://jacob:NotMyPassword@git.ghostlyco.de/revxx14/new-site.git`
 5. Once the terminal finishes the cloning process, it'll present you with a new blank line. You're now ready to start editing.
 
#### Step 2: Setting up for SCSS
 
Most of my projects use [SCSS](http://sass-lang.com/) (pronounced sass) instead of plain CSS. If you're unfamiliar with SCSS, don't worry, it's basically just a superset of regular CSS. It's got a very quick learning curve.

I also use a modified version of the [SMACSS methodology](https://smacss.com/) (pronounced smacks) for my CSS. This is just an organizational system that helps keep code clean, clear, and organized. You can read up on SMACSS at the link above.

The last thing you need to know about SCSS is that it compiles in to regular CSS. You'll notice that this project has a ton of stuff split out in to seperate files in the assets/styles folder. Each of these folders contains a number of files, named like _screen.scss, _screen_xl.scscs, _screen_xs.scss, etc. These files all get included in their matching file under assets/views. For example, assets/styles/layout/content/_screen_xs.scss gets included in assets/styles/views/_screen_xs.scss. Then those views get included in matching media queries under assets/styles/all.scss, which then gets compiled in to all.css. Trust me, it sounds more complicated than it is :)

I personally use [Prepros](https://prepros.io/) to compile my SCSS, as well as my JavaScript. Here's how you get it set up:
 
 1. Vist [prepros.io](https://prepros.io/) and download the application. It's available for Windows, Mac, and Linux. If you'd prefer not to pay $30 for the application, just download the free trial. It never expires, and has all the same features. It'll just display a pop-up asking you to register every so often.
 2. Once it's finished downloading, install it.
 3. Open up Prepros, and the folder containing your cloned repo in to it. A plus icon should appear, at which point, you'll just drop the folder in to Prepros.
 4. After a few seconds, you'll see a folder tree. Browse to assets/styles, and then click on all.scss.
 5. A sidebar should appear, with several options. You'll need to make sure "Auto Compile," "AutoPrefix CSS," "Use LibSass," and "SourceMaps" are all checked, and you'll need to change "Output Style" to "Compressed." Finally, you may need to change the output path to assets/styles/all.css.

That's it! As long as Prepros is open, it'll automatically watch any changes you make to the SCSS and automatically compile it in to all.css.

#### Step 4: Setting up for JS

Most of my javascript gets compiled in to all.js, just like my SCSS gets compiled in to all.css. I make exceptions for code that's only used on certain pages, or code that's for legacy browsers. Everything gets compiled with Prepros, so if you've followed Step 3, this is a very similar process.

 1. Open Prepros, and make sure this project is selected.
 2. Browse to assest/scripts, and then click on scripts.js.
 3. A sidebar should appear, with several options. You'll need to make sure "Auto Compile," "SourceMaps," "Uglify JS," and "Mangle Variables" are all checked, and you may need to change the "Output Path" to assets/scripts/all.js.

#### Step 5: Previewing the Site

Prepros has a very nice feature that will auto-refresh after you make changes to a project. It's super easy to enable.

 1. Open Prepros, and make sure this project is selected.
 2. Clck "Preview" in the top left corner.
 3. Click "Open Live Preview."

And that's it! You're all set up and ready to start editing this project.

## Additional Notes

 - You can always ask for help. If you don't know any other way to contact me, I'm [@revxx14](https://twitter.com/revxx14) on Twitter.
 - If you're trying to push, and getting an error saying something along the lines of "Remote end hung up unexpectedly," open a terminal window in this project and type: `git config http.postBuffer 524288000`
