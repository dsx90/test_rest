<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';

?>
<div class="site-index">

</div>

<?php $this->registerJs(<<<JS
var tableTag = 'scroll-table',
    url = "/rest/news",
    // links = [];
    up = btoa('admin:admin')
    at = '';

    
var start = function (){
    request(function(data, status, request) {
        console.log(data);
        // requestLinks(request);
        requestLinks(data._links)
        
        var table = $(createTable(data.items));
        table.attr('id', tableTag);
    
        $('.site-index').append(table);
        at = '';
    })
}
start();
function requestLinks(links){
    if(typeof links.next.href !== "undefined"){
        url = links.next.href;
    }
}
// function requestLinks(request){
//     var headers = request.getResponseHeader('link').split(',');
//    
//     $.each(headers, function (i, item){
//         d = item.split(';')
//         key = d[1].replace('rel=',"").trim()
//         value = d[0].replace(/[<>]/g,"").trim()
//         links[key] = value
//     })
//    
//     if(typeof links['next'] !== "undefined"){
//         url = links['next'];
//     }
// }

function request(done){
    console.log(url);
    
    var params = {
      url: url,
      success: done,
      error: function(d, s, r) {
        if (d.status == 401){
            $.post( "site/up", {up:up}, function( data ) {
                at = data;
                start();
            });
        }
      }
    };
    if(at){
        params['data'] = {'access-token': at};
    }
    $.ajax(params)

}


function createTable(data){
    return '<table class="table">'+
    createBody('thead', [Object.keys(data[0])])+
    createBody('tbody',data)
    +'</table>'
}

function createBody(tag, data){
    var body = '';
    $(data).each(function (key, row){
        body += createRow('td',row);
    })
    return '<'+tag+'>'+body+'</'+tag+'>';
}

function createRow(tag, row){
    var tr = '';
    $.each(row, function (key, text){
        tr+=  '<'+tag+'>'+text+'</'+tag+'>'
    })
    return '<tr>'+tr+'</tr>';
}

var windowHeight = $(window).height(),
    pending = false;

$(window).scroll(function(){
    var self = $('#'+tableTag+' tr:last'),
        height;
    
        if (self.height() >= windowHeight) {
            height = self.offset().top + windowHeight - 100;
        } else {
            height = self.offset().top + self.height();
        }
        
        if (($(document).scrollTop() + windowHeight >= height) && pending === false) {
            pending = true;
            
            request(function(data, status, request) {
                //requestLinks(request)
                requestLinks(data._links)
                var td = '';
                $(data.items).each(function (key, row){
                    td += createRow('td',row);
                })
                
                $('#'+tableTag+' tbody').append(td)
                pending = false;
            })
        }

});

JS
)?>
