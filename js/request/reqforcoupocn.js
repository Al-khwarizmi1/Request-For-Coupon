/**
 * @Author       : Apptha team
 * @package      : Apptha_Request_Coupon
 * @copyright    : Copyright (c) 2011 (www.apptha.com)
 * @license      : http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 * @Date         : July 2012
 */
var baseurl ='';
var productorder = Array();
var c = 0;
var req = jQuery.noConflict();
req(document).ready(function(){    
    req("body").append("<div class='black_overlay'></div><a onclick ='closefun()'>Close</a><div class='white_content'></div>");
    var clickfn;
    var Productid;
    req(".btn-cart").each(function(){ 
        clickfn = (req(this).attr('onclick'));
        
        var test = clickfn.split(/product/);
        var url = clickfn.split(/checkout/);
      
        var clickUrl = url[0].split("setLocation('");
        if(clickUrl[1] && baseurl=='')
        {
                
            baseurl= clickUrl[1];
        
        }
            
        if(test[1])
        {
            var test1= test[1].split(/\//); 
            if(req.browser.msie < 9){
                Productid = test1[0];
            } else {
                Productid = test1[1];
            }    
        
            productorder[c] = Productid;
        
            req(this).attr("id","product"+Productid);
            c++;
        }
        
    });

    req.ajax({
        async:true,
        type: "POST",
        url: baseurl+'reqforcoupon/index/req',
        success: function(data){
            var temp = data.split(","); 
          
            for(var i=0;i<productorder.length;i++){
              
                if(temp.in_array(productorder[i]))
                { 
                    req(".btn-cart").eq(i).after('<ul class="add-to-links"><li> <a style="color: #203548 !important;font-weight: bold;" href="javascript:void(0);" onclick="popupfun('+req.trim(productorder[i])+')" class="link-request"></a></li></ul>');
                }
        
            }
            loadform();   
        } 
    });       
});
Array.prototype.in_array = function(p_val) {
    for(var i = 0, l = this.length; i < l; i++) {
        if(this[i] == p_val) {
            return true;
        }
    }
    return false;
}
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
    req("#messagefun").live().hide();
     
    document.getElementById("productid").value = pid;
    document.getElementById("token").value = tonke_no; 
};
function closefun(){
    //alert('hi');
    document.getElementById('req_Name').value = "";    
    document.getElementById('req_email').value = "";
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
function loadform(){
    req.ajax({
        async:true,
        type: "POST",
        url: baseurl+'reqforcoupon/index/index',
        success: function(data){
            
            req(".white_content").html(data) ;
            
        }
    });
};   
