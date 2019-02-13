/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
var baseurl;
var req = jQuery.noConflict();
req(document).ready(function(){
    req("body").append("<div class='black_overlay'></div><a onclick ='closefun()'>Close</a><div class='white_content'></div>");
    var clickfn;
    var clickfnUrl;
    var Productid;
    clickfnUrl = req("#product_addtocart_form").attr('action');
    
    var url = clickfnUrl.split(/checkout/);
    var clickUrl = url[0];
    var baseurl= clickUrl;

    clickfn = req("#product_addtocart_form").attr('action');
    var test = clickfn.split(/product/);
    var test1= test[1].split(/\//);
    if(req.browser.msie){
        Productid = test1[0];
    } else {
        Productid = test1[1];
    }           
    // req(this).attr("id","product"+Productid);
    req(".btn-cart").attr("id","product"+Productid);
       
    req.ajax({
        async:true,
        type: "POST",
        url: baseurl+'reqforcoupon/index/req',
        success: function(data){
            var temp = data.split(",");
            for(var i=0;i<temp.length;i++){
                //req("#product"+req.trim(temp[i]) ).after('<button type="button" onclick="popupfun('+req.trim(temp[i])+')"style="clear: both; display: block; margin-top: 2px;margin-bottom: -7px;" class="button" ><span><span>Request for coupon</span></span></button>');
                req(".add-to-links").prepend('<li style="padding-right:10px;"> <a style="color: #1E7EC8 !important;font-weight: normal !important;" href="javascript:void(0);" onclick="popupfun('+req.trim(temp[i])+')" class="link-request"></a></li>');
                break;
            }
            loadform(baseurl)               
        }
    });
          
});

//To get the cookies value
function getCookie(c_name)
{
    var i,x,y,ARRcookies=document.cookie.split(";");
    for (i=0;i<ARRcookies.length;i++)
    {
        x=ARRcookies[i].substr(0,ARRcookies[i].indexOf("="));
        y=ARRcookies[i].substr(ARRcookies[i].indexOf("=")+1);
        x=x.replace(/^\s+|\s+$/g,"");
        if (x==c_name)
        {
            return unescape(y);
        }
    }
}
//To set cookies for checking the random number
function setCookie(c_name,value,exdays)
{
    var exdate=new Date();
    exdate.setDate(exdate.getDate() + exdays);
    var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
    document.cookie=c_name + "=" + c_value;
}
function popupfun(pid){
    var tonke_no = Math.floor((Math.random()*1000000)+1);
    setCookie("tonke_no",tonke_no); 
    req(".white_content").show();
    req(".black_overlay").show();
    req("#messagefun").hide();
    document.getElementById("productid").value = pid;
    document.getElementById("token").value = tonke_no;
}; 
function loadform(baseurl){
    req.ajax({
        async:true,
        type: "POST",
        url:baseurl+'reqforcoupon/index/index',
        success: function(data){
            req(".white_content").html(data) ;
        }
    });
};
function closefun(){
    //alert('hi');
    document.getElementById('req_Name').value = " ";    
    document.getElementById('req_email').value = " ";
    document.getElementById('token').value = "";
    document.getElementById('req_comment').value = "Write your request";
     document.getElementById('error_first').innerHTML = " ";
    document.getElementById('error_emails').innerHTML = " ";
    document.getElementById('err_message').innerHTML = " ";
    req(".white_content").hide();
    req(".black_overlay").hide();
     req("ul.#formdiv").fadeIn();
    req("#messagefun").hide();
};