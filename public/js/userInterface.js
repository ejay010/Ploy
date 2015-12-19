/**
 * Created by ejay010 on 11/11/15.
 *
 *
 */

var form1 = document.forms['generalPost'];
var form2 = document.forms['vicePost'];
var form3 = document.forms['transportPost'];
var form4 = document.forms['foodPost'];
var token = $("meta[name='_token']").attr('content');
$(document).ready(function() {

    $('#general').click(function(){
        $('.vicePost').css("display", "none");
        $('.transportPost').css("display", "none");
        $('.foodPost').css("display", "none");
        $('.generalPost').css("display", "block");
        clearActive();
        markAsActive('#generalLI');
    });

    $('#food').click(function(){
        $('.vicePost').css("display", "none");
        $('.transportPost').css("display", "none");
        $('.generalPost').css("display", "none");
        $('.foodPost').css("display", "block");
        clearActive();
        markAsActive('#foodLI');
    });

    $('#vice').click(function(){
        $('.transportPost').css("display", "none");
        $('.foodPost').css("display", "none");
        $('.generalPost').css("display", "none");
        $('.vicePost').css("display", "block");
        clearActive();
        markAsActive('#viceLI');
    });

    $('#transport').click(function(){
        $('.vicePost').css("display", "none");
        $('.foodPost').css("display", "none");
        $('.generalPost').css("display", "none");
        $('.transportPost').css("display", "block");
        clearActive();
        markAsActive('#transpLI');
    });

    function markAsActive(listid){
        $(listid).addClass('active');
    }

    function clearActive(){
        $('#generalLI').removeClass('active');
        $('#foodLI').removeClass('active');
        $('#viceLI').removeClass('active');
        $('#transpLI').removeClass('active');
    }

    listenforPosts();
    listenforBids($('meta[name="theU"]').attr('content'));

    $("#biddingModal").on('hide.bs.modal', function(event){
        clearBidTrack(this);
    });

    $("#bidTrackingModal").on('hide.bs.modal', function(event){
        clearBidTrack(this);
    })

});

var mapholder = document.getElementById("mapper");

var map;

function initMap(){
    map = new google.maps.Map(mapholder, {
        center: {lat: -34.397, lng: 150.644},
        zoom: 6
    });

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var pos = {
                lat: position.coords.latitude,
                lng: position.coords.longitude
            };
            form1.elements['loc'].value = JSON.stringify(pos);
            form2.elements['loc'].value = JSON.stringify(pos);
            form3.elements['loc'].value = JSON.stringify(pos);
            form4.elements['loc'].value = JSON.stringify(pos);

            map.setCenter(pos);
            map.setZoom(15);

            $.get("/mapThePosts", function(data){
                for (i = 0; i < data.length; i++){
                    addMapMarker(JSON.parse(data[i].location), data[i].title, data[i].jobpost_id, data[i].type, data[i].content, map);
                }
            });
        })
    } else {
        alert("Your location services are disabled, No one will know where you are!!");
    }
}

function addMapMarker(location, thetitle, theId, theType, theContent, themap){
    var marker = new google.maps.Marker({
        position: location,
        map: themap,
        title: thetitle,
        marker_id: theId,
        type: theType,
        content: theContent
    });

    var infoContent = createMarkerInfoContent(theContent, theType, theId, thetitle);

    var infowindow = new google.maps.InfoWindow({
        content: infoContent
    });

    marker.addListener('click', function(){
        infowindow.open(themap, marker);
    })
}

function createMarkerInfoContent(theContent, theType, theId, theTitle){
    return '<div id="content" class="'+theType+'">' +
        '<div id="sideNotice">' +
        '</div>' +
        '<h2 id="firstHeading" style="color: #080808">'+theTitle+'</h2>' +
        '<div id="bodyContent" style="color: #080808">' +
        '<p>'+theContent+'</p>' +
        '<p>The id is' + theId + ' </p>' +
        '</div>' +
        '</div>';
}

function submitAPost(theFormID){
    var form = $(theFormID);
    var url = form.attr("action");
    $.post(url, form.serialize(), function(data, status, jq){
        form.trigger("reset");
        initMap();
    });
}

function showABiddingmodel(theModelId, thepid){
    var modal = $(theModelId);
    modal.find('.modal-body input[name="pid"]').val(thepid);
    $(theModelId).modal('show');
    getAllbids(thepid, theModelId);

    var pusher = new Pusher('a00ffb18c6c056d26f6b');
    var channel = pusher.subscribe('theJobChannel');
    channel.bind('userPlaceBid', function(data){
        addToBidTrack(theModelId, data.newBid);
    });
}

function submitABid(theFormID, theModelId){

        var form = $(theFormID);
        var url = form.attr("action");
        $.post(url, form.serialize(), function(data, status, jq){
            form.trigger("reset");
        });
}

function sendMessage(theform){
    var form = $(theform);
    var url = form.attr("action");
    var sentMessage = $('textarea#message').val();
    $.post(url, form.serialize(), function(data, status, jq){
        if(status === 'success'){
            addMessageToChatBox(sentMessage, '#chatModal');
            form.trigger("reset");
        }
    })
}

function listenforPosts(){
    var pusher = new Pusher('a00ffb18c6c056d26f6b');
    var channel = pusher.subscribe('theJobChannel');
    channel.bind('userAddedPost', function(data){
        $('#postsContainer').prepend(addToJobList(data.newjob));
    });
}

function watchBidTrack(theModal, thepid){
    var modal = $(theModal);
    modal.find('.modal-body input[name="pid"]').val(thepid);
    $(theModal).modal('show');
    getAllbids(thepid, theModal);

    var pusher = new Pusher('a00ffb18c6c056d26f6b');
    var channel = pusher.subscribe('theJobChannel');
    channel.bind('userPlaceBid', function(data){
        modal.find('.modal-body input[name="bid"]').val(data.newBid.bid);
        addToBidTrack(theModal, data.newBid);
    });
}

function accept(theform){
    var form = $(theform);
    var url = form.attr("action");
    $.post(url, form.serialize(), function(data, status, jq){
        alert(data);
        if(data === 'false'){
            alert('There are no bids bruh, try making a respectable job');
        } else {
            $('#chatModal').modal('show');
        }
    });
}

function listenforBids(theU){
    var pusher = new Pusher('a00ffb18c6c056d26f6b', {authEndpoint: '/userlogin/mychannel', auth: {
        headers: {
            'X-CSRF-TOKEN': token
        }
    }});
    var ChannelName = 'private-theU'+theU;
    var chanel = pusher.subscribe(ChannelName);
    var theDiv = $('#bidAlertBox');
    var theNode;
    chanel.bind('bidToJob', function(data){
        if($('#bidTrackingModal').hasClass('in')){

        }else {
            addToBidNotificationArea(data);
        }
    });

    chanel.bind('bidsuccess', function(data){
        alert(data.m);
        $('#chatModal').modal('show');
    });
    chanel.bind('inithandshake', function(data){
        theNode = data.node;
        setNode(theNode, '#chatModal');
        alert(theNode);
    });
    chanel.bind('incomeingMessage', function(data){
        alert(data.m);
        addMessageToChatBox(data.m, '#chatModal');
    });
}

function setNode(node, themodal){
    var Modal = $(themodal);
    Modal.find('#node').val(node);
}

function addMessageToChatBox(message, themodal){
    var theDiv = $(themodal).find('#log');
    var theMessage = '<p style="color: #080808;">'+message+'</p>';
    theDiv.append(theMessage);
}

function addToJobList(data){
    return '<div id="aPost" class="panel panel-default '+data.type+' ">'+
    '<div class="panel-heading">'+
    '<h3 class="panel-title"><small>your job: </small><a href="#" onclick="watchBidTrack('+'#bidTrackingModal'+', '+'{{ $posts->jobpost_id }}'+'); return false;">'+data.title+'</a></h3>'+
'</div>'+
'<div class="panel-body" style="color: #000000;">'+
    '<p>'+data.content+'</p>'+
'</div>'+
'<div class="panel-footer" style="color: #000;">'+
    '<p style="font-size: 9pt; margin-bottom: auto;">Initial Payout: $'+data.salary+'</p>'+
'</div>'+
'</div>';
}

function addToBidTrack(theModal, data){
    var Modal = $(theModal);
    var theTrack = Modal.find('#bidTrack');
    var elementToPrepend = '<a href="#" class="list-group-item">'+
    '<h4 class="list-group-item-heading">$'+data.bid+'</h4>'+
    '<p class="list-group-item-text">'+data.comment+'</p>'+
    '</a>';
    theTrack.prepend(elementToPrepend);
}

function clearBidTrack(theModal){
    var Modal = $(theModal);
    Modal.find('#bidTrack').empty();
}

function getAllbids(id, theModal){
    $.get('/userlogin/bidpost/getbids/'+id, null, function(data){
        for(i = 0; i < data.length; i++){
            addToBidTrack(theModal, data[i]);
            $(theModal).find('.modal-body input[name="bidid"]').val(data[i].bidId);
        }
    }, 'json');
}

function addToBidNotificationArea(data){
    var theDiv = $('#bidAlertBox');
    var elementToPrepend = '<a href="#" class="list-group-item">'+
        '<h4 class="list-group-item-heading">'+data.theTitle+'</h4>'+
        '<p class="list-group-item-text">latest bid: $'+data.theBidValue+'</p>'+
        '<p class="list-group-item-text">Comment: '+data.theComment+'</p></a>';
    theDiv.prepend(elementToPrepend);
}

function beginTransaction(){

}