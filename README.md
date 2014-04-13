# How much time you’ve spent watching TV shows

## Demo: <a href="http://tiii.me">http://tiii.me</a>

A small web app that calculates how much time you’ve spent watching TV shows. TV shows API by [Trakt](http://trakt.tv/). Autocomplete plugin by [Igor Vaynberg](https://github.com/ivaynberg/select2).

## Forking / Using the project yourself
If you’re gonna use this project, please change the Trakt API key in the JavaScript file to your own API key. You can get one by signing up here: [https://trakt.tv/join](https://trakt.tv/join) and then going to the [API docs](https://trakt.tv/api-docs/authentication).

The line 33 with the API key is inside [/scripts/partials/tv_show.js](https://github.com/alexcican/tiii.me/blob/gh-pages/scripts/partials/_tv-show.js#L33), but you’ll also want to compile a new app.min.js file (that’s the file that gets referenced in [the HTML, line 101](https://github.com/alexcican/tiii.me/blob/gh-pages/index.html#L101)).

## License
All demos found in this repo are licensed under the MIT License.

The MIT License (MIT)
Copyright (c) 2014, Alex Cican.

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

Email me: <a href="mailto:alex@alexcican.com">alex@alexcican.com</a>

**[Alex Cican](http://alexcican.com)**