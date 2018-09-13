
<!DOCTYPE html>

<!--
Copyright 2017 Google Inc.

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at

    http://www.apache.org/licenses/LICENSE-2.0

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
-->

<html lang="en">
<head>

<!-- Global site tag (gtag.js) - Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-33848682-1"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag() {
  window.dataLayer.push(arguments);
}
gtag('js', new Date());
gtag('config', 'UA-33848682-1');
</script>

<meta charset="utf-8">
<meta name="description" content="Simplest possible examples of HTML, CSS and JavaScript.">
<meta name="author" content="//samdutton.com">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta itemprop="name" content="simpl.info: simplest possible examples of HTML, CSS and JavaScript">
<meta itemprop="image" content="/images/icons/icon192.png">
<meta id="theme-color" name="theme-color" content="#fff">

<link rel="icon" href="/images/icons/icon192.jpg">

<base target="_blank">


<title>MediaStreamTrack.getSources()</title>

<link rel="stylesheet" href="css/main.css">

<style>
div.select {
  margin: 0 0 1em 0;
}
</style>

</head>

<body>

<div id="container">

  <h1><a href="../../index.html" title="simpl.info home page">simpl.info</a> MediaStreamTrack.getSources</h1>

  <div class="select">
    <label for="audioSource">Audio source: </label><select id="audioSource"></select>
  </div>

  <div class="select">
    <label for="videoSource">Video source: </label><select id="videoSource"></select>
  </div>

  <video muted autoplay></video>

  <script src="js/main.js"></script>

  <p>This demo requires Chrome 30 or later.</p>

  <p>For more information, see <a href="https://www.html5rocks.com/en/tutorials/getusermedia/intro/" title="Media capture article by Eric Bidelman on HTML5 Rocks">Capturing Audio &amp; Video in HTML5</a> on HTML5 Rocks.</p>

<a href="https://github.com/samdutton/simpl/blob/gh-pages/getusermedia/sources/js/main.js" title="View source for this page on GitHub" id="viewSource">View source on GitHub</a>
<input type="file" accept="image/*" capture="camera" />
</div>

<script src="../../js/lib/ga.js"></script>

</body>
</html>
