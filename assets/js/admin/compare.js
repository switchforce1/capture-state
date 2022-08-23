import '../../styles/admin/compare.css'
import $ from 'jquery';

let dataString = $('.compare-main-data dd span').html();
var obj = JSON.parse(dataString);
var newJSON = JSON.stringify(obj, undefined, 2);
$('.compare-main-data dd span').html('<pre>'+ newJSON +'</pre>');
console.log(dataString);
