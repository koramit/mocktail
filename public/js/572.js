"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[572],{8284:(e,t,n)=>{n.d(t,{Z:()=>a});var r=n(3645),o=n.n(r)()((function(e){return e[1]}));o.push([e.id,"@media print{@page{margin:1cm;padding:0}body{height:296mm;margin:0;width:210mm}.print-p-0{padding:0}.text-print-size{font-size:10pt!important}.avoid-page-break{display:block;page-break-inside:avoid}.new-page{display:block;page-break-before:always}label.form-label{font-size:10pt!important;font-weight:400;padding-bottom:.25rem}}",""]);const a=o},3645:e=>{e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var n=e(t);return t[2]?"@media ".concat(t[2]," {").concat(n,"}"):n})).join("")},t.i=function(e,n,r){"string"==typeof e&&(e=[[null,e,""]]);var o={};if(r)for(var a=0;a<this.length;a++){var i=this[a][0];null!=i&&(o[i]=!0)}for(var l=0;l<e.length;l++){var c=[].concat(e[l]);r&&o[c[0]]||(n&&(c[2]?c[2]="".concat(n," and ").concat(c[2]):c[2]=n),t.push(c))}},t}},3379:(e,t,n)=>{var r,o=function(){return void 0===r&&(r=Boolean(window&&document&&document.all&&!window.atob)),r},a=function(){var e={};return function(t){if(void 0===e[t]){var n=document.querySelector(t);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}e[t]=n}return e[t]}}(),i=[];function l(e){for(var t=-1,n=0;n<i.length;n++)if(i[n].identifier===e){t=n;break}return t}function c(e,t){for(var n={},r=[],o=0;o<e.length;o++){var a=e[o],c=t.base?a[0]+t.base:a[0],s=n[c]||0,d="".concat(c," ").concat(s);n[c]=s+1;var u=l(d),f={css:a[1],media:a[2],sourceMap:a[3]};-1!==u?(i[u].references++,i[u].updater(f)):i.push({identifier:d,updater:g(f,t),references:1}),r.push(d)}return r}function s(e){var t=document.createElement("style"),r=e.attributes||{};if(void 0===r.nonce){var o=n.nc;o&&(r.nonce=o)}if(Object.keys(r).forEach((function(e){t.setAttribute(e,r[e])})),"function"==typeof e.insert)e.insert(t);else{var i=a(e.insert||"head");if(!i)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");i.appendChild(t)}return t}var d,u=(d=[],function(e,t){return d[e]=t,d.filter(Boolean).join("\n")});function f(e,t,n,r){var o=n?"":r.media?"@media ".concat(r.media," {").concat(r.css,"}"):r.css;if(e.styleSheet)e.styleSheet.cssText=u(t,o);else{var a=document.createTextNode(o),i=e.childNodes;i[t]&&e.removeChild(i[t]),i.length?e.insertBefore(a,i[t]):e.appendChild(a)}}function m(e,t,n){var r=n.css,o=n.media,a=n.sourceMap;if(o?e.setAttribute("media",o):e.removeAttribute("media"),a&&"undefined"!=typeof btoa&&(r+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(a))))," */")),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}var p=null,h=0;function g(e,t){var n,r,o;if(t.singleton){var a=h++;n=p||(p=s(t)),r=f.bind(null,n,a,!1),o=f.bind(null,n,a,!0)}else n=s(t),r=m.bind(null,n,t),o=function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(n)};return r(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;r(e=t)}else o()}}e.exports=function(e,t){(t=t||{}).singleton||"boolean"==typeof t.singleton||(t.singleton=o());var n=c(e=e||[],t);return function(e){if(e=e||[],"[object Array]"===Object.prototype.toString.call(e)){for(var r=0;r<n.length;r++){var o=l(n[r]);i[o].references--}for(var a=c(e,t),s=0;s<n.length;s++){var d=l(n[s]);0===i[d].references&&(i[d].updater(),i.splice(d,1))}n=a}}}},9811:(e,t,n)=>{n.d(t,{Z:()=>c});var r=n(5166),o={class:"w-full"},a={key:0,class:"form-label text-print-size"},i=["innerHTML"];const l={props:{label:{type:String,default:""},data:{type:String,default:""},format:{type:String,default:""}},computed:{formattedData:function(){if(!this.data||!this.format)return this.data;if("date"===this.format){var e=this.data.split("-");return e[2]+" "+["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][parseInt(e[1])]+" "+e[0]}return this.data}},render:function(e,t,n,l,c,s){return(0,r.openBlock)(),(0,r.createElementBlock)("div",o,[n.label?((0,r.openBlock)(),(0,r.createElementBlock)("label",a,(0,r.toDisplayString)(n.label)+" :",1)):(0,r.createCommentVNode)("",!0),(0,r.createElementVNode)("div",{class:"break-words text-print-size",innerHTML:s.formattedData},null,8,i)])}},c=l},7578:(e,t,n)=>{n.d(t,{Z:()=>f});var r=n(5166),o={class:"flex fixed w-full bottom-0"},a={class:"w-1/4",id:"footer-left"},i={class:"w-3/4 text-right",id:"footer-right"};var l=n(3379),c=n.n(l),s=n(8284),d={insert:"head",singleton:!1};c()(s.Z,d);s.Z.locals;const u={render:function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[(0,r.renderSlot)(e.$slots,"default"),(0,r.createElementVNode)("div",o,[(0,r.createElementVNode)("div",a,[(0,r.renderSlot)(e.$slots,"footer-left")]),(0,r.createElementVNode)("div",i,[(0,r.renderSlot)(e.$slots,"footer-right")])])])}},f=u},4511:(e,t,n)=>{n.d(t,{Z:()=>a});var r=n(5166);const o={watch:{"$page.props.flash":{immediate:!0,deep:!0,handler:function(){document.title=this.$page.props.flash.title}}},created:function(){var e=this;this.baseUrl=document.querySelector("meta[name=base-url]").content;var t=Date.now(),n=this.baseUrl+"/session-timeout",r=parseInt(document.querySelector("meta[name=session-lifetime-seconds]").content);window.addEventListener("focus",(function(){Date.now()-t>r&&window.axios.post(n).then((function(){return t=Date.now()})).catch((function(){return location.reload()}))})),this.eventBus.on("need-confirm",(function(t){setTimeout((function(){return e.$nextTick((function(){return e.$refs.confirmForm.open(t)}))}),300)}))},mounted:function(){this.$nextTick((function(){var e=document.getElementById("page-loading-indicator");document.querySelector("body").classList.toggle("bg-soft-theme-light"),e&&e.remove(),setTimeout((function(){return window.print()}),500)}))},render:function(e,t,n,o,a,i){return(0,r.openBlock)(),(0,r.createElementBlock)("div",null,[(0,r.renderSlot)(e.$slots,"default")])}},a=o},7572:(e,t,n)=>{n.r(t),n.d(t,{default:()=>w});var r=n(5166),o={class:"px-12 py-6 print-p-0"},a=(0,r.createElementVNode)("h2",{class:"font-semibold pb-2 border-b-2 border-dashed text-xl text-center"}," ใบส่งตัว ",-1),i=(0,r.createElementVNode)("h3",{class:"font-normal underline mt-4"}," ข้อมูลเบื้องต้น ",-1),l={class:"mt-1 grid grid-rows-4 grid-flow-col gap-2"},c=(0,r.createElementVNode)("h3",{class:"font-normal underline mt-4"}," Vital Signs ล่าสุด ",-1),s={class:"mt-1 grid grid-rows-3 grid-flow-col gap-2"},d={class:"font-normal underline mt-4"},u={class:"mt-1"},f=(0,r.createElementVNode)("h3",{class:"font-normal underline mt-4"}," คำสั่งการรักษา ",-1),m={key:0,class:"avoid-page-break"},p=(0,r.createElementVNode)("h3",{class:"font-normal underline mt-4"}," เพิ่มเติม ",-1),h={key:0,class:"text-print-size"},g={key:1,class:"text-print-size"};var v=n(9811),b=n(4511),k=n(7578);const y={layout:b.Z,components:{DisplayInput:v.Z,Paper:k.Z},props:{contents:{type:Object,required:!0},configs:{type:Object,required:!0}},computed:{filteredTreatments:function(){var e=this,t=[];return Object.keys(this.contents.treatments).forEach((function(n){var r=e.configs.treatments.findIndex((function(e){return e.name===n}));-1!==r&&t.push(e.configs.treatments[r])})),t}},render:function(e,t,n,v,b,k){var y=(0,r.resolveComponent)("display-input"),w=(0,r.resolveComponent)("paper");return(0,r.openBlock)(),(0,r.createBlock)(w,null,{default:(0,r.withCtx)((function(){return[(0,r.createElementVNode)("div",o,[a,i,(0,r.createElementVNode)("div",l,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(n.configs.patient.filter((function(e){return n.contents.patient[e.name]})),(function(e,t){var o;return(0,r.openBlock)(),(0,r.createBlock)(y,{key:t,label:e.label,data:n.contents.patient[e.name],format:null!==(o=e.format)&&void 0!==o?o:""},null,8,["label","data","format"])})),128))]),c,(0,r.createElementVNode)("div",s,[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(n.configs.vital_signs,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)(y,{key:t,label:e.label,data:n.contents.vital_signs[e.name]},null,8,["label","data"])})),128))]),((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(n.configs.topics,(function(e,t){return(0,r.openBlock)(),(0,r.createElementBlock)(r.Fragment,{key:t},[(0,r.createElementVNode)("h3",d,(0,r.toDisplayString)(e.label),1),(0,r.createElementVNode)("div",u,[(0,r.createVNode)(y,{data:n.contents[e.name]},null,8,["data"])])],64)})),128)),f,(0,r.createElementVNode)("div",{class:(0,r.normalizeClass)(["mt-1 grid grid-flow-col gap-2",{"grid-rows-1":k.filteredTreatments.length<=3,"grid-rows-2":k.filteredTreatments.length>3}])},[((0,r.openBlock)(!0),(0,r.createElementBlock)(r.Fragment,null,(0,r.renderList)(k.filteredTreatments,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)(y,{key:t,label:e.label,data:n.contents.treatments[e.name]},null,8,["label","data"])})),128))],2),n.contents.remark?((0,r.openBlock)(),(0,r.createElementBlock)("div",m,[p,(0,r.createVNode)(y,{data:n.contents.remark},null,8,["data"])])):(0,r.createCommentVNode)("",!0)])]})),"footer-right":(0,r.withCtx)((function(){return[e.$page.props.user.roles.includes("nurse")?((0,r.openBlock)(),(0,r.createElementBlock)("p",h," เมื่อ "+(0,r.toDisplayString)(n.contents.author.updated_at),1)):((0,r.openBlock)(),(0,r.createElementBlock)("p",g," Electronic Signed by "+(0,r.toDisplayString)(n.contents.author.name)+" ว. "+(0,r.toDisplayString)(n.contents.author.pln)+" เมื่อ "+(0,r.toDisplayString)(n.contents.author.updated_at),1))]})),_:1})}},w=y}}]);