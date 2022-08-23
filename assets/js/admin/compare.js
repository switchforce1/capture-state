import '../../styles/admin/compare.css'
import $ from 'jquery';

let dataString = $('.compare-main-data dd span').html();
var obj = JSON.parse(dataString);
var newJSON = JSON.stringify(obj, undefined, 2);
$('.compare-main-data dd span').html('<pre>'+ newJSON +'</pre>');

let revertDataString = $('.compare-revert-data dd span').html();
var objRevert = JSON.parse(revertDataString);
var revertFormattedJson = JSON.stringify(objRevert, undefined, 2);
$('.compare-revert-data dd span').html('<pre>'+ revertFormattedJson +'</pre>');
if (dataString) {
    alert('No comparison data , try to execute <Refresh compare data>');
}
