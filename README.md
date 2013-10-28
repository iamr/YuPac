YuPac
=====

YuPac--A YUI based javascript packer for Coda 2.
[Download it!](https://github.com/mjvotaw/YuPac/archive/master.zip)

What Does This Do?
-------------------

YuPac does one thing: it minifies and combines javascript files into one compressed file.
It doesn't overwrite the original files, and it doesn't create separate minified versions of them.

How Do You Use It?
------------------

In you html, select the javascript files you want to compress:

![Select javascript html](/images/select.png)

And hit YuPac. It will minify and combine the files, preserving their order in your script. When it's done, it will place a file in the root of your project, and a script tag after your selection:

![YuPac Adds a script tag](/images/compressed.png)

YuPac will notify you of its succes, showing a list of files that were successfully compressed (and any that were skipped).


Why Would You Use It?
---------------------

Sometimes, code deployment doesn't need to be complicated. Sometimes, you just need to pack a bunch of javascript files, and don't feel like turning on and setting up yet another app just to handle this.

Things That It Deals With Pretty Well
-------------------------------------

Yupac will ignore commented code, either <!-- -->, /* */, or // type comments.

Limitations
-----------

YuPac will only minify local javascript files. If you include a src from an external site, it will be ignored.

It currently offers no customization of YUI parameters or output directory or filename. If there is interest in these features, I will add them in the future.


Changelog
=========

v1.0.1
------
- YuPac now uses the path of the current file instead of the current Site's base path. Now you can compress files that are pointed to from within a subdirectory, and everything will work how you'd think it should, instead of, well, not.
- Fixed a really stupid issue that didn't allow script tags with single quotes (') to be compressed.

v1.0.0
------
Initial release.