/**
 * Created by Adriani6 on 5/15/2015.
 */

function appendMetaTag(){

    var meta = document.createElement('meta');
    meta.httpEquiv = "X-UA-Compatible";
    meta.content = "IE=edge";
    document.getElementsByTagName('head')[0].appendChild(meta);
}