YuPac
=====

YuPac--A YUI based javascript packer for Coda 2.
[Download it![(https://github.com/mjvotaw/YuPac/archive/master.zip)

What Does This Do?
-------------------

YuPac does one thing: it minifies and combines javascript files into one compressed file.
It doesn't overwrite the original files, and it doesn't create separate minified versions of them.

How Do You Use It?
------------------

In you html, select the javascript files you want to compress:

>	 <script type="text/javascript" src="js/plugins/CSSPlugin.min.js"></script>
>    <script type="text/javascript" src="js/easing/EasePack.min.js"></script>
>	 <script src="js/slider.js" type="text/javascript"></script>
>    <script src="js/fit.js" type="text/javascript"></script>
>    <script src="js/animations.js" type="text/javascript"></script>
>    <script src="js/blog.js"></script>
>    <script src="js/main.js" type="text/javascript"></script>

And hit YuPac. It will minify and combine the files, preserving their order in your script. When it's done, it will place a file in the root of your project, and a script tag after your selection:

>	<script src="compressed.js"></script>

YuPac will notify you of its succes, showing a list of files that were successfully compressed (and any that were skipped).

Things That It Deals With Pretty Well
-------------------------------------

Yupac will ignore commented code, either <!-- -->, /* */, or // type comments.

Limitations
-----------

YuPac will only minify local javascript files. If you include a src from an external site, it will be ignored.

It currently offers no customization of YUI parameters or output directory or filename. If there is interest in these features, I will add them in the future.


Changelog
=========

v1.0.0
------
Initial release.