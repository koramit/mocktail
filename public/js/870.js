(self.webpackChunk=self.webpackChunk||[]).push([[870],{3903:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>o});var r=n(3645),a=n.n(r)()((function(e){return e[1]}));a.push([e.id,"@media print{@page{margin:1cm;padding:0}body{margin:0;width:210mm;height:296mm}.print-p-0{padding:0}.text-print-size{font-size:10pt!important}.avoid-page-break{display:block;page-break-inside:avoid}label.form-label{font-size:10pt!important;padding-bottom:.25rem;font-weight:400}}",""]);const o=a},3645:e=>{"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var n=e(t);return t[2]?"@media ".concat(t[2]," {").concat(n,"}"):n})).join("")},t.i=function(e,n,r){"string"==typeof e&&(e=[[null,e,""]]);var a={};if(r)for(var o=0;o<this.length;o++){var i=this[o][0];null!=i&&(a[i]=!0)}for(var s=0;s<e.length;s++){var l=[].concat(e[s]);r&&a[l[0]]||(n&&(l[2]?l[2]="".concat(n," and ").concat(l[2]):l[2]=n),t.push(l))}},t}},4538:(e,t,n)=>{"use strict";n.d(t,{Z:()=>s});var r=n(5166),a={class:"w-full"},o={key:0,class:"form-label text-print-size"};const i={props:{label:{type:String,default:""},data:{type:String,default:""},format:{type:String,default:""}},computed:{formattedData:function(){if(!this.format)return this.data;if("date"===this.format){var e=this.data.split("-");return e[2]+" "+["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][parseInt(e[1])]+" "+e[0]}return this.data}},render:function(e,t,n,i,s,l){return(0,r.openBlock)(),(0,r.createBlock)("div",a,[n.label?((0,r.openBlock)(),(0,r.createBlock)("label",o,(0,r.toDisplayString)(n.label)+" :",1)):(0,r.createCommentVNode)("",!0),(0,r.createVNode)("div",{class:"break-words text-print-size",innerHTML:l.formattedData},null,8,["innerHTML"])])}},s=i},2200:(e,t,n)=>{"use strict";n.d(t,{Z:()=>l});var r=n(5166),a={class:"flex fixed w-full bottom-0"},o={class:"w-1/4",id:"footer-left"},i={class:"w-3/4 text-right",id:"footer-right"};const s={render:function(e,t){return(0,r.openBlock)(),(0,r.createBlock)("div",null,[(0,r.renderSlot)(e.$slots,"default"),(0,r.createVNode)("div",a,[(0,r.createVNode)("div",o,[(0,r.renderSlot)(e.$slots,"footer-left")]),(0,r.createVNode)("div",i,[(0,r.renderSlot)(e.$slots,"footer-right")])])])}},l=s},3245:(e,t,n)=>{"use strict";n.d(t,{Z:()=>o});var r=n(5166);const a={watch:{"$page.props.flash":{immediate:!0,deep:!0,handler:function(){document.title=this.$page.props.flash.title}}},created:function(){var e=this;this.baseUrl=document.querySelector("meta[name=base-url]").content;var t=Date.now(),n=this.baseUrl+"/session-timeout",r=parseInt(document.querySelector("meta[name=session-lifetime-seconds]").content);window.addEventListener("focus",(function(){Date.now()-t>r&&window.axios.post(n).then((function(){return t=Date.now()})).catch((function(){return location.reload()}))})),this.eventBus.on("need-confirm",(function(t){setTimeout((function(){return e.$nextTick((function(){return e.$refs.confirmForm.open(t)}))}),300)}))},mounted:function(){this.$nextTick((function(){var e=document.getElementById("page-loading-indicator");e&&e.remove(),setTimeout((function(){return window.print()}),500)}))}};n(2708);a.render=function(e,t,n,a,o,i){return(0,r.openBlock)(),(0,r.createBlock)("div",null,[(0,r.renderSlot)(e.$slots,"default")])};const o=a},8870:(e,t,n)=>{"use strict";n.r(t),n.d(t,{default:()=>y});var r=n(5166),a={class:"px-12 py-6 print-p-0"},o=(0,r.createVNode)("h2",{class:"font-semibold pb-2 border-b-2 border-dashed text-xl text-center"}," ใบส่งตัว ",-1),i=(0,r.createVNode)("h3",{class:"font-normal underline mt-4"}," ข้อมูลเบื้องต้น ",-1),s={class:"mt-1 grid grid-rows-4 grid-flow-col gap-2"},l=(0,r.createVNode)("h3",{class:"font-normal underline mt-4"}," Vital Signs ล่าสุด ",-1),c={class:"mt-1 grid grid-rows-3 grid-flow-col gap-2"},d={class:"font-normal underline mt-4"},u={class:"mt-1"},f=(0,r.createVNode)("h3",{class:"font-normal underline mt-4"}," คำสั่งการรักษา ",-1),p={key:0,class:"avoid-page-break"},m=(0,r.createVNode)("h3",{class:"font-normal underline mt-4"}," เพิ่มเติม ",-1),h={class:"text-print-size"};var g=n(4538),v=n(3245),b=n(2200);const k={layout:v.Z,components:{DisplayInput:g.Z,Paper:b.Z},props:{contents:{type:Object,required:!0},configs:{type:Object,required:!0}},computed:{filteredTreatments:function(){var e=this,t=[];return Object.keys(this.contents.treatments).forEach((function(n){var r=e.configs.treatments.findIndex((function(e){return e.name===n}));-1!==r&&t.push(e.configs.treatments[r])})),t}},render:function(e,t,n,g,v,b){var k=(0,r.resolveComponent)("display-input"),y=(0,r.resolveComponent)("paper");return(0,r.openBlock)(),(0,r.createBlock)(y,null,{default:(0,r.withCtx)((function(){return[(0,r.createVNode)("div",a,[o,i,(0,r.createVNode)("div",s,[((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(n.configs.patient,(function(e,t){var a;return(0,r.openBlock)(),(0,r.createBlock)(k,{key:t,label:e.label,data:n.contents.patient[e.name],format:null!==(a=e.format)&&void 0!==a?a:""},null,8,["label","data","format"])})),128))]),l,(0,r.createVNode)("div",c,[((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(n.configs.vital_signs,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)(k,{key:t,label:e.label,data:n.contents.vital_signs[e.name]},null,8,["label","data"])})),128))]),((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(n.configs.topics,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)(r.Fragment,{key:t},[(0,r.createVNode)("h3",d,(0,r.toDisplayString)(e.label),1),(0,r.createVNode)("div",u,[(0,r.createVNode)(k,{data:n.contents[e.name]},null,8,["data"])])],64)})),128)),f,(0,r.createVNode)("div",{class:["mt-1 grid grid-flow-col gap-2",{"grid-rows-1":b.filteredTreatments.length<=3,"grid-rows-2":b.filteredTreatments.length>3}]},[((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(b.filteredTreatments,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)(k,{key:t,label:e.label,data:n.contents.treatments[e.name]},null,8,["label","data"])})),128))],2),n.contents.remark?((0,r.openBlock)(),(0,r.createBlock)("div",p,[m,(0,r.createVNode)(k,{data:n.contents.remark},null,8,["data"])])):(0,r.createCommentVNode)("",!0)])]})),"footer-right":(0,r.withCtx)((function(){return[(0,r.createVNode)("p",h,(0,r.toDisplayString)(n.contents.author.name)+" ว. "+(0,r.toDisplayString)(n.contents.author.pln)+" เมื่อ "+(0,r.toDisplayString)(n.contents.author.updated_at),1)]})),_:1})}},y=k},2708:(e,t,n)=>{var r=n(3903);r.__esModule&&(r=r.default),"string"==typeof r&&(r=[[e.id,r,""]]),r.locals&&(e.exports=r.locals);(0,n(5346).Z)("61f3eb0f",r,!0,{})},5346:(e,t,n)=>{"use strict";function r(e,t){for(var n=[],r={},a=0;a<t.length;a++){var o=t[a],i=o[0],s={id:e+":"+a,css:o[1],media:o[2],sourceMap:o[3]};r[i]?r[i].parts.push(s):n.push(r[i]={id:i,parts:[s]})}return n}n.d(t,{Z:()=>m});var a="undefined"!=typeof document;if("undefined"!=typeof DEBUG&&DEBUG&&!a)throw new Error("vue-style-loader cannot be used in a non-browser environment. Use { target: 'node' } in your Webpack config to indicate a server-rendering environment.");var o={},i=a&&(document.head||document.getElementsByTagName("head")[0]),s=null,l=0,c=!1,d=function(){},u=null,f="data-vue-ssr-id",p="undefined"!=typeof navigator&&/msie [6-9]\b/.test(navigator.userAgent.toLowerCase());function m(e,t,n,a){c=n,u=a||{};var i=r(e,t);return h(i),function(t){for(var n=[],a=0;a<i.length;a++){var s=i[a];(l=o[s.id]).refs--,n.push(l)}t?h(i=r(e,t)):i=[];for(a=0;a<n.length;a++){var l;if(0===(l=n[a]).refs){for(var c=0;c<l.parts.length;c++)l.parts[c]();delete o[l.id]}}}}function h(e){for(var t=0;t<e.length;t++){var n=e[t],r=o[n.id];if(r){r.refs++;for(var a=0;a<r.parts.length;a++)r.parts[a](n.parts[a]);for(;a<n.parts.length;a++)r.parts.push(v(n.parts[a]));r.parts.length>n.parts.length&&(r.parts.length=n.parts.length)}else{var i=[];for(a=0;a<n.parts.length;a++)i.push(v(n.parts[a]));o[n.id]={id:n.id,refs:1,parts:i}}}}function g(){var e=document.createElement("style");return e.type="text/css",i.appendChild(e),e}function v(e){var t,n,r=document.querySelector("style["+f+'~="'+e.id+'"]');if(r){if(c)return d;r.parentNode.removeChild(r)}if(p){var a=l++;r=s||(s=g()),t=y.bind(null,r,a,!1),n=y.bind(null,r,a,!0)}else r=g(),t=B.bind(null,r),n=function(){r.parentNode.removeChild(r)};return t(e),function(r){if(r){if(r.css===e.css&&r.media===e.media&&r.sourceMap===e.sourceMap)return;t(e=r)}else n()}}var b,k=(b=[],function(e,t){return b[e]=t,b.filter(Boolean).join("\n")});function y(e,t,n,r){var a=n?"":r.css;if(e.styleSheet)e.styleSheet.cssText=k(t,a);else{var o=document.createTextNode(a),i=e.childNodes;i[t]&&e.removeChild(i[t]),i.length?e.insertBefore(o,i[t]):e.appendChild(o)}}function B(e,t){var n=t.css,r=t.media,a=t.sourceMap;if(r&&e.setAttribute("media",r),u.ssrId&&e.setAttribute(f,t.id),a&&(n+="\n/*# sourceURL="+a.sources[0]+" */",n+="\n/*# sourceMappingURL=data:application/json;base64,"+btoa(unescape(encodeURIComponent(JSON.stringify(a))))+" */"),e.styleSheet)e.styleSheet.cssText=n;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(n))}}}}]);