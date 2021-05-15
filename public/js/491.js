(self.webpackChunk=self.webpackChunk||[]).push([[491],{7130:(e,c,t)=>{"use strict";t.d(c,{Z:()=>p});var l=t(5166),o={class:"flex items-center"},a={class:"form-label"},r={class:"inline-block"},n={key:0,class:"text-red-700 text-sm"};var s=t(9038);function i(e,c){var t=Object.keys(e);if(Object.getOwnPropertySymbols){var l=Object.getOwnPropertySymbols(e);c&&(l=l.filter((function(c){return Object.getOwnPropertyDescriptor(e,c).enumerable}))),t.push.apply(t,l)}return t}function m(e){for(var c=1;c<arguments.length;c++){var t=null!=arguments[c]?arguments[c]:{};c%2?i(Object(t),!0).forEach((function(c){d(e,c,t[c])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(t)):i(Object(t)).forEach((function(c){Object.defineProperty(e,c,Object.getOwnPropertyDescriptor(t,c))}))}return e}function d(e,c,t){return c in e?Object.defineProperty(e,c,{value:t,enumerable:!0,configurable:!0,writable:!0}):e[c]=t,e}const u={emits:["update:modelValue","autosave"],components:{Icon:t(4643).Z},props:{modelValue:{type:String,default:""},label:{type:String,required:!0},name:{type:String,required:!0},noteId:{type:Number,required:!0},error:{type:String,default:""},readonly:{type:Boolean}},data:function(){return{form:(0,s.cI)({file:null,name:this.name}),baseUrl:this.$page.props.app.baseUrl,show:!1,loading:!1}},methods:{fileInput:function(e){var c=this;this.form.file=e.target.files[0],this.form.transform((function(e){return m(m({},e),{},{note_id:c.noteId})})).post("".concat(this.baseUrl,"/uploads"),{preserveScroll:!0,onFinish:function(){c.show||(c.show=!0),c.$emit("update:modelValue",c.form.errors.filename)}})}},render:function(e,c,t,s,i,m){var d=(0,l.resolveComponent)("icon");return(0,l.openBlock)(),(0,l.createBlock)("div",null,[(0,l.createVNode)("div",o,[(0,l.createVNode)("p",a,[(0,l.createVNode)("span",r,(0,l.toDisplayString)(t.label),1),i.loading||i.form.processing?((0,l.openBlock)(),(0,l.createBlock)(d,{key:0,class:"ml-1 w-4 h-4 inline-block opacity-25 animate-spin",name:"circle-notch"})):(0,l.createCommentVNode)("",!0)]),t.readonly?(0,l.createCommentVNode)("",!0):((0,l.openBlock)(),(0,l.createBlock)("button",{key:0,class:"ml-4",onClick:c[1]||(c[1]=function(c){return e.$refs.useCamera.click()})},[(0,l.createVNode)(d,{class:"w-4 h-4 text-thick-theme-light",name:"camera"})])),t.readonly?(0,l.createCommentVNode)("",!0):((0,l.openBlock)(),(0,l.createBlock)("button",{key:1,class:"ml-4",onClick:c[2]||(c[2]=function(c){return e.$refs.useGallery.click()})},[(0,l.createVNode)(d,{class:"w-4 h-4 text-thick-theme-light",name:"image"})])),t.modelValue?((0,l.openBlock)(),(0,l.createBlock)("button",{key:2,class:"ml-4",onClick:c[3]||(c[3]=function(e){return i.show=!i.show})},[(0,l.createVNode)(d,{class:"w-4 h-4 text-dark-theme-light",name:i.show?"eyes-slash":"eyes"},null,8,["name"])])):(0,l.createCommentVNode)("",!0)]),t.error?((0,l.openBlock)(),(0,l.createBlock)("div",n,(0,l.toDisplayString)(t.error),1)):(0,l.createCommentVNode)("",!0),void 0!==t.modelValue&&i.show?((0,l.openBlock)(),(0,l.createBlock)("img",{key:1,src:"".concat(i.baseUrl,"/uploads/").concat(t.modelValue),onLoadstart:c[4]||(c[4]=function(e){return i.loading=!0}),onLoad:c[5]||(c[5]=function(c){return e.$nextTick((function(){return i.loading=!1}))}),alt:""},null,40,["src"])):(0,l.createCommentVNode)("",!0),(0,l.createVNode)("input",{class:"hidden",type:"file",ref:"useCamera",onInput:c[6]||(c[6]=function(){return m.fileInput&&m.fileInput.apply(m,arguments)}),capture:"environment",accept:"image/*"},null,544),(0,l.createVNode)("input",{class:"hidden",type:"file",ref:"useGallery",onInput:c[7]||(c[7]=function(){return m.fileInput&&m.fileInput.apply(m,arguments)}),accept:"image/*"},null,544)])}},p=u},4152:(e,c,t)=>{"use strict";t.d(c,{Z:()=>n});var l=t(5166),o={class:"text-sm font-semibold text-dark-theme-light mt-4 flex items-center"},a={key:0};const r={components:{Icon:t(4643).Z},props:{contact:{type:Object,required:!0}},data:function(){return{showContact:!1}},render:function(e,c,t,r,n,s){var i=(0,l.resolveComponent)("icon");return(0,l.openBlock)(),(0,l.createBlock)("div",o,[(0,l.createVNode)("p",null,(0,l.toDisplayString)(t.contact.name),1),(0,l.createVNode)("button",{onClick:c[1]||(c[1]=function(e){return n.showContact=!n.showContact}),class:"mx-4 text-alt-theme-light"},[(0,l.createVNode)(i,{class:"w-4 h-4",name:"phone-square"})]),n.showContact?((0,l.openBlock)(),(0,l.createBlock)("p",a,(0,l.toDisplayString)(t.contact.tel_no),1)):(0,l.createCommentVNode)("",!0)])}},n=r},4538:(e,c,t)=>{"use strict";t.d(c,{Z:()=>n});var l=t(5166),o={class:"w-full"},a={key:0,class:"form-label text-print-size"};const r={props:{label:{type:String,default:""},data:{type:String,default:""},format:{type:String,default:""}},computed:{formattedData:function(){if(!this.format)return this.data;if("date"===this.format){var e=this.data.split("-");return e[2]+" "+["","Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec"][parseInt(e[1])]+" "+e[0]}return this.data}},render:function(e,c,t,r,n,s){return(0,l.openBlock)(),(0,l.createBlock)("div",o,[t.label?((0,l.openBlock)(),(0,l.createBlock)("label",a,(0,l.toDisplayString)(t.label)+" :",1)):(0,l.createCommentVNode)("",!0),(0,l.createVNode)("div",{class:"break-words text-print-size",innerHTML:s.formattedData},null,8,["innerHTML"])])}},n=r},4643:(e,c,t)=>{"use strict";t.d(c,{Z:()=>ne});var l=t(5166),o={key:0,viewBox:"0 0 320 512"},a=(0,l.createVNode)("path",{fill:"currentColor",d:"M143 256.3L7 120.3c-9.4-9.4-9.4-24.6 0-33.9l22.6-22.6c9.4-9.4 24.6-9.4 33.9 0l96.4 96.4 96.4-96.4c9.4-9.4 24.6-9.4 33.9 0L313 86.3c9.4 9.4 9.4 24.6 0 33.9l-136 136c-9.4 9.5-24.6 9.5-34 .1zm34 192l136-136c9.4-9.4 9.4-24.6 0-33.9l-22.6-22.6c-9.4-9.4-24.6-9.4-33.9 0L160 352.1l-96.4-96.4c-9.4-9.4-24.6-9.4-33.9 0L7 278.3c-9.4 9.4-9.4 24.6 0 33.9l136 136c9.4 9.5 24.6 9.5 34 .1z"},null,-1),r={key:1,viewBox:"0 0 448 512"},n=(0,l.createVNode)("path",{fill:"currentColor",d:"M277.37 11.98C261.08 4.47 243.11 0 224 0c-53.69 0-99.5 33.13-118.51 80h81.19l90.69-68.02zM342.51 80c-7.9-19.47-20.67-36.2-36.49-49.52L239.99 80h102.52zM224 256c70.69 0 128-57.31 128-128 0-5.48-.95-10.7-1.61-16H97.61c-.67 5.3-1.61 10.52-1.61 16 0 70.69 57.31 128 128 128zM80 299.7V512h128.26l-98.45-221.52A132.835 132.835 0 0 0 80 299.7zM0 464c0 26.51 21.49 48 48 48V320.24C18.88 344.89 0 381.26 0 422.4V464zm256-48h-55.38l42.67 96H256c26.47 0 48-21.53 48-48s-21.53-48-48-48zm57.6-128h-16.71c-22.24 10.18-46.88 16-72.89 16s-50.65-5.82-72.89-16h-7.37l42.67 96H256c44.11 0 80 35.89 80 80 0 18.08-6.26 34.59-16.41 48H400c26.51 0 48-21.49 48-48v-41.6c0-74.23-60.17-134.4-134.4-134.4z"},null,-1),s={key:2,viewBox:"0 0 576 512"},i=(0,l.createVNode)("path",{fill:"currentColor",d:"M288 115L69.47 307.71c-1.62 1.46-3.69 2.14-5.47 3.35V496a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V311.1c-1.7-1.16-3.72-1.82-5.26-3.2zm96 261a8 8 0 0 1-8 8h-56v56a8 8 0 0 1-8 8h-48a8 8 0 0 1-8-8v-56h-56a8 8 0 0 1-8-8v-48a8 8 0 0 1 8-8h56v-56a8 8 0 0 1 8-8h48a8 8 0 0 1 8 8v56h56a8 8 0 0 1 8 8zm186.69-139.72l-255.94-226a39.85 39.85 0 0 0-53.45 0l-256 226a16 16 0 0 0-1.21 22.6L25.5 282.7a16 16 0 0 0 22.6 1.21L277.42 81.63a16 16 0 0 1 21.17 0L527.91 283.9a16 16 0 0 0 22.6-1.21l21.4-23.82a16 16 0 0 0-1.22-22.59z"},null,-1),m={key:3,viewBox:"0 0 640 512"},d=(0,l.createVNode)("path",{fill:"currentColor",d:"M528 224H272c-8.8 0-16 7.2-16 16v144H64V144c0-8.8-7.2-16-16-16H16c-8.8 0-16 7.2-16 16v352c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16v-48h512v48c0 8.8 7.2 16 16 16h32c8.8 0 16-7.2 16-16V336c0-61.9-50.1-112-112-112zM136 96h126.1l27.6 55.2c5.9 11.8 22.7 11.8 28.6 0L368 51.8 390.1 96H512c8.8 0 16-7.2 16-16s-7.2-16-16-16H409.9L382.3 8.8C376.4-3 359.6-3 353.7 8.8L304 108.2l-19.9-39.8c-1.4-2.7-4.1-4.4-7.2-4.4H136c-4.4 0-8 3.6-8 8v16c0 4.4 3.6 8 8 8zm24 256c35.3 0 64-28.7 64-64s-28.7-64-64-64-64 28.7-64 64 28.7 64 64 64z"},null,-1),u={key:4,viewBox:"0 0 448 512"},p=(0,l.createVNode)("path",{fill:"currentColor",d:"M224.3 273l-136 136c-9.4 9.4-24.6 9.4-33.9 0l-22.6-22.6c-9.4-9.4-9.4-24.6 0-33.9l96.4-96.4-96.4-96.4c-9.4-9.4-9.4-24.6 0-33.9L54.3 103c9.4-9.4 24.6-9.4 33.9 0l136 136c9.5 9.4 9.5 24.6.1 34zm192-34l-136-136c-9.4-9.4-24.6-9.4-33.9 0l-22.6 22.6c-9.4 9.4-9.4 24.6 0 33.9l96.4 96.4-96.4 96.4c-9.4 9.4-9.4 24.6 0 33.9l22.6 22.6c9.4 9.4 24.6 9.4 33.9 0l136-136c9.4-9.2 9.4-24.4 0-33.8z"},null,-1),h={key:5,viewBox:"0 0 352 512"},k=(0,l.createVNode)("path",{fill:"currentColor",d:"M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z"},null,-1),v={key:6,viewBox:"0 0 512 512"},f=(0,l.createVNode)("path",{fill:"currentColor",d:"M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm101.8-262.2L295.6 256l62.2 62.2c4.7 4.7 4.7 12.3 0 17l-22.6 22.6c-4.7 4.7-12.3 4.7-17 0L256 295.6l-62.2 62.2c-4.7 4.7-12.3 4.7-17 0l-22.6-22.6c-4.7-4.7-4.7-12.3 0-17l62.2-62.2-62.2-62.2c-4.7-4.7-4.7-12.3 0-17l22.6-22.6c4.7-4.7 12.3-4.7 17 0l62.2 62.2 62.2-62.2c4.7-4.7 12.3-4.7 17 0l22.6 22.6c4.7 4.7 4.7 12.3 0 17z"},null,-1),B={key:7,viewBox:"0 0 512 512"},g=(0,l.createVNode)("path",{fill:"currentColor",d:"M256 8C119.043 8 8 119.083 8 256c0 136.997 111.043 248 248 248s248-111.003 248-248C504 119.083 392.957 8 256 8zm0 110c23.196 0 42 18.804 42 42s-18.804 42-42 42-42-18.804-42-42 18.804-42 42-42zm56 254c0 6.627-5.373 12-12 12h-88c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h12v-64h-12c-6.627 0-12-5.373-12-12v-24c0-6.627 5.373-12 12-12h64c6.627 0 12 5.373 12 12v100h12c6.627 0 12 5.373 12 12v24z"},null,-1),V={key:8,viewBox:"0 0 384 512"},y=(0,l.createVNode)("path",{fill:"currentColor",d:"M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM96 424c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm0-96c-13.3 0-24-10.7-24-24s10.7-24 24-24 24 10.7 24 24-10.7 24-24 24zm96-192c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm128 368c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16zm0-96c0 4.4-3.6 8-8 8H168c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h144c4.4 0 8 3.6 8 8v16z"},null,-1),z={key:9,viewBox:"0 0 640 512"},C=(0,l.createVNode)("path",{fill:"currentColor",d:"M624 352h-16V243.9c0-12.7-5.1-24.9-14.1-33.9L494 110.1c-9-9-21.2-14.1-33.9-14.1H416V48c0-26.5-21.5-48-48-48H48C21.5 0 0 21.5 0 48v320c0 26.5 21.5 48 48 48h16c0 53 43 96 96 96s96-43 96-96h128c0 53 43 96 96 96s96-43 96-96h48c8.8 0 16-7.2 16-16v-32c0-8.8-7.2-16-16-16zM160 464c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm144-248c0 4.4-3.6 8-8 8h-56v56c0 4.4-3.6 8-8 8h-48c-4.4 0-8-3.6-8-8v-56h-56c-4.4 0-8-3.6-8-8v-48c0-4.4 3.6-8 8-8h56v-56c0-4.4 3.6-8 8-8h48c4.4 0 8 3.6 8 8v56h56c4.4 0 8 3.6 8 8v48zm176 248c-26.5 0-48-21.5-48-48s21.5-48 48-48 48 21.5 48 48-21.5 48-48 48zm80-208H416V144h44.1l99.9 99.9V256z"},null,-1),b={key:10,viewBox:"0 0 512 512"},N=(0,l.createVNode)("path",{fill:"currentColor",d:"M512 144v288c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V144c0-26.5 21.5-48 48-48h88l12.3-32.9c7-18.7 24.9-31.1 44.9-31.1h125.5c20 0 37.9 12.4 44.9 31.1L376 96h88c26.5 0 48 21.5 48 48zM376 288c0-66.2-53.8-120-120-120s-120 53.8-120 120 53.8 120 120 120 120-53.8 120-120zm-32 0c0 48.5-39.5 88-88 88s-88-39.5-88-88 39.5-88 88-88 88 39.5 88 88z"},null,-1),w={key:11,viewBox:"0 0 512 512"},x=(0,l.createVNode)("path",{fill:"currentColor",d:"M464 64H48C21.49 64 0 85.49 0 112v288c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V112c0-26.51-21.49-48-48-48zm-6 336H54a6 6 0 0 1-6-6V118a6 6 0 0 1 6-6h404a6 6 0 0 1 6 6v276a6 6 0 0 1-6 6zM128 152c-22.091 0-40 17.909-40 40s17.909 40 40 40 40-17.909 40-40-17.909-40-40-40zM96 352h320v-80l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L192 304l-39.515-39.515c-4.686-4.686-12.284-4.686-16.971 0L96 304v48z"},null,-1),H={key:12,viewBox:"0 0 512 512"},M=(0,l.createVNode)("path",{fill:"currentColor",d:"M288 39.056v16.659c0 10.804 7.281 20.159 17.686 23.066C383.204 100.434 440 171.518 440 256c0 101.689-82.295 184-184 184-101.689 0-184-82.295-184-184 0-84.47 56.786-155.564 134.312-177.219C216.719 75.874 224 66.517 224 55.712V39.064c0-15.709-14.834-27.153-30.046-23.234C86.603 43.482 7.394 141.206 8.003 257.332c.72 137.052 111.477 246.956 248.531 246.667C393.255 503.711 504 392.788 504 256c0-115.633-79.14-212.779-186.211-240.236C302.678 11.889 288 23.456 288 39.056z"},null,-1),L={key:13,viewBox:"0 0 512 512"},O=(0,l.createVNode)("path",{fill:"currentColor",d:"M504 256c0 136.997-111.043 248-248 248S8 392.997 8 256C8 119.083 119.043 8 256 8s248 111.083 248 248zm-248 50c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"},null,-1),S={key:14,viewBox:"0 0 448 512"},I=(0,l.createVNode)("path",{fill:"currentColor",d:"M268 416h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12zM432 80h-82.41l-34-56.7A48 48 0 0 0 274.41 0H173.59a48 48 0 0 0-41.16 23.3L98.41 80H16A16 16 0 0 0 0 96v16a16 16 0 0 0 16 16h16v336a48 48 0 0 0 48 48h288a48 48 0 0 0 48-48V128h16a16 16 0 0 0 16-16V96a16 16 0 0 0-16-16zM171.84 50.91A6 6 0 0 1 177 48h94a6 6 0 0 1 5.15 2.91L293.61 80H154.39zM368 464H80V128h288zm-212-48h24a12 12 0 0 0 12-12V188a12 12 0 0 0-12-12h-24a12 12 0 0 0-12 12v216a12 12 0 0 0 12 12z"},null,-1),j={key:15,viewBox:"0 0 576 512"},D=(0,l.createVNode)("path",{fill:"currentColor",d:"M402.6 83.2l90.2 90.2c3.8 3.8 3.8 10 0 13.8L274.4 405.6l-92.8 10.3c-12.4 1.4-22.9-9.1-21.5-21.5l10.3-92.8L388.8 83.2c3.8-3.8 10-3.8 13.8 0zm162-22.9l-48.8-48.8c-15.2-15.2-39.9-15.2-55.2 0l-35.4 35.4c-3.8 3.8-3.8 10 0 13.8l90.2 90.2c3.8 3.8 10 3.8 13.8 0l35.4-35.4c15.2-15.3 15.2-40 0-55.2zM384 346.2V448H64V128h229.8c3.2 0 6.2-1.3 8.5-3.5l40-40c7.6-7.6 2.2-20.5-8.5-20.5H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h352c26.5 0 48-21.5 48-48V306.2c0-10.7-12.9-16-20.5-8.5l-40 40c-2.2 2.3-3.5 5.3-3.5 8.5z"},null,-1),A={key:16,viewBox:"0 0 576 512"},Z=(0,l.createVNode)("path",{fill:"currentColor",d:"M528.3 46.5H388.5c-48.1 0-89.9 33.3-100.4 80.3-10.6-47-52.3-80.3-100.4-80.3H48c-26.5 0-48 21.5-48 48v245.8c0 26.5 21.5 48 48 48h89.7c102.2 0 132.7 24.4 147.3 75 .7 2.8 5.2 2.8 6 0 14.7-50.6 45.2-75 147.3-75H528c26.5 0 48-21.5 48-48V94.6c0-26.4-21.3-47.9-47.7-48.1zM242 311.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5V289c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V251zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H78.2c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm259.3 121.7c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.9c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5V228c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5v22.9zm0-60.9c0 1.9-1.5 3.5-3.5 3.5H337.5c-1.9 0-3.5-1.5-3.5-3.5v-22.8c0-1.9 1.5-3.5 3.5-3.5h160.4c1.9 0 3.5 1.5 3.5 3.5V190z"},null,-1),q={key:17,viewBox:"0 0 576 512"},F=(0,l.createVNode)("path",{fill:"currentColor",d:"M288 144a110.94 110.94 0 0 0-31.24 5 55.4 55.4 0 0 1 7.24 27 56 56 0 0 1-56 56 55.4 55.4 0 0 1-27-7.24A111.71 111.71 0 1 0 288 144zm284.52 97.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400c-98.65 0-189.09-55-237.93-144C98.91 167 189.34 112 288 112s189.09 55 237.93 144C477.1 345 386.66 400 288 400z"},null,-1),P={key:18,viewBox:"0 0 640 512"},T=(0,l.createVNode)("path",{fill:"currentColor",d:"M634 471L36 3.51A16 16 0 0 0 13.51 6l-10 12.49A16 16 0 0 0 6 41l598 467.49a16 16 0 0 0 22.49-2.49l10-12.49A16 16 0 0 0 634 471zM296.79 146.47l134.79 105.38C429.36 191.91 380.48 144 320 144a112.26 112.26 0 0 0-23.21 2.47zm46.42 219.07L208.42 260.16C210.65 320.09 259.53 368 320 368a113 113 0 0 0 23.21-2.46zM320 112c98.65 0 189.09 55 237.93 144a285.53 285.53 0 0 1-44 60.2l37.74 29.5a333.7 333.7 0 0 0 52.9-75.11 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64c-36.7 0-71.71 7-104.63 18.81l46.41 36.29c18.94-4.3 38.34-7.1 58.22-7.1zm0 288c-98.65 0-189.08-55-237.93-144a285.47 285.47 0 0 1 44.05-60.19l-37.74-29.5a333.6 333.6 0 0 0-52.89 75.1 32.35 32.35 0 0 0 0 29.19C89.72 376.41 197.08 448 320 448c36.7 0 71.71-7.05 104.63-18.81l-46.41-36.28C359.28 397.2 339.89 400 320 400z"},null,-1),U={key:19,viewBox:"0 0 512 512"},$=(0,l.createVNode)("path",{fill:"currentColor",d:"M256 8C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 48c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m140.204 130.267l-22.536-22.718c-4.667-4.705-12.265-4.736-16.97-.068L215.346 303.697l-59.792-60.277c-4.667-4.705-12.265-4.736-16.97-.069l-22.719 22.536c-4.705 4.667-4.736 12.265-.068 16.971l90.781 91.516c4.667 4.705 12.265 4.736 16.97.068l172.589-171.204c4.704-4.668 4.734-12.266.067-16.971z"},null,-1),_={key:20,viewBox:"0 0 512 512"},E=(0,l.createVNode)("path",{fill:"currentColor",d:"M497.941 273.941c18.745-18.745 18.745-49.137 0-67.882l-160-160c-18.745-18.745-49.136-18.746-67.883 0l-256 256c-18.745 18.745-18.745 49.137 0 67.882l96 96A48.004 48.004 0 0 0 144 480h356c6.627 0 12-5.373 12-12v-40c0-6.627-5.373-12-12-12H355.883l142.058-142.059zm-302.627-62.627l137.373 137.373L265.373 416H150.628l-80-80 124.686-124.686z"},null,-1),J={key:21,viewBox:"0 0 384 512"},G=(0,l.createVNode)("path",{fill:"currentColor",d:"M336 64h-80c0-35.3-28.7-64-64-64s-64 28.7-64 64H48C21.5 64 0 85.5 0 112v352c0 26.5 21.5 48 48 48h288c26.5 0 48-21.5 48-48V112c0-26.5-21.5-48-48-48zM192 40c13.3 0 24 10.7 24 24s-10.7 24-24 24-24-10.7-24-24 10.7-24 24-24zm96 304c0 4.4-3.6 8-8 8h-56v56c0 4.4-3.6 8-8 8h-48c-4.4 0-8-3.6-8-8v-56h-56c-4.4 0-8-3.6-8-8v-48c0-4.4 3.6-8 8-8h56v-56c0-4.4 3.6-8 8-8h48c4.4 0 8 3.6 8 8v56h56c4.4 0 8 3.6 8 8v48zm0-192c0 4.4-3.6 8-8 8H104c-4.4 0-8-3.6-8-8v-16c0-4.4 3.6-8 8-8h176c4.4 0 8 3.6 8 8v16z"},null,-1),K={key:22,viewBox:"0 0 448 512"},Q=(0,l.createVNode)("path",{fill:"currentColor",d:"M400 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h352c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zM94 416c-7.033 0-13.057-4.873-14.616-11.627l-14.998-65a15 15 0 0 1 8.707-17.16l69.998-29.999a15 15 0 0 1 17.518 4.289l30.997 37.885c48.944-22.963 88.297-62.858 110.781-110.78l-37.886-30.997a15.001 15.001 0 0 1-4.289-17.518l30-69.998a15 15 0 0 1 17.16-8.707l65 14.998A14.997 14.997 0 0 1 384 126c0 160.292-129.945 290-290 290z"},null,-1),R={key:23,viewBox:"0 0 512 512"},W=(0,l.createVNode)("path",{fill:"currentColor",d:"M487.976 0H24.028C2.71 0-8.047 25.866 7.058 40.971L192 225.941V432c0 7.831 3.821 15.17 10.237 19.662l80 55.98C298.02 518.69 320 507.493 320 487.98V225.941l184.947-184.97C520.021 25.896 509.338 0 487.976 0z"},null,-1),X={key:24,viewBox:"0 0 384 512"},Y=(0,l.createVNode)("path",{fill:"currentColor",d:"M224 136V0H24C10.7 0 0 10.7 0 24v464c0 13.3 10.7 24 24 24h336c13.3 0 24-10.7 24-24V160H248c-13.2 0-24-10.8-24-24zm60.1 106.5L224 336l60.1 93.5c5.1 8-.6 18.5-10.1 18.5h-34.9c-4.4 0-8.5-2.4-10.6-6.3C208.9 405.5 192 373 192 373c-6.4 14.8-10 20-36.6 68.8-2.1 3.9-6.1 6.3-10.5 6.3H110c-9.5 0-15.2-10.5-10.1-18.5l60.3-93.5-60.3-93.5c-5.2-8 .6-18.5 10.1-18.5h34.8c4.4 0 8.5 2.4 10.6 6.3 26.1 48.8 20 33.6 36.6 68.5 0 0 6.1-11.7 36.6-68.5 2.1-3.9 6.2-6.3 10.6-6.3H274c9.5-.1 15.2 10.4 10.1 18.4zM384 121.9v6.1H256V0h6.1c6.4 0 12.5 2.5 17 7l97.9 98c4.5 4.5 7 10.6 7 16.9z"},null,-1),ee={key:25,viewBox:"0 0 576 512"},ce=(0,l.createVNode)("path",{fill:"currentColor",d:"M527.9 224H480v-48c0-26.5-21.5-48-48-48H272l-64-64H48C21.5 64 0 85.5 0 112v288c0 26.5 21.5 48 48 48h400c16.5 0 31.9-8.5 40.7-22.6l79.9-128c20-31.9-3-73.4-40.7-73.4zM48 118c0-3.3 2.7-6 6-6h134.1l64 64H426c3.3 0 6 2.7 6 6v42H152c-16.8 0-32.4 8.8-41.1 23.2L48 351.4zm400 282H72l77.2-128H528z"},null,-1),te={key:26,viewBox:"0 0 448 512"},le=(0,l.createVNode)("path",{fill:"currentColor",d:"M446.2 270.4c-6.2-19-26.9-29.1-46-22.9l-45.4 15.1-30.3-90 45.4-15.1c19.1-6.2 29.1-26.8 23-45.9-6.2-19-26.9-29.1-46-22.9l-45.4 15.1-15.7-47c-6.2-19-26.9-29.1-46-22.9-19.1 6.2-29.1 26.8-23 45.9l15.7 47-93.4 31.2-15.7-47c-6.2-19-26.9-29.1-46-22.9-19.1 6.2-29.1 26.8-23 45.9l15.7 47-45.3 15c-19.1 6.2-29.1 26.8-23 45.9 5 14.5 19.1 24 33.6 24.6 6.8 1 12-1.6 57.7-16.8l30.3 90L78 354.8c-19 6.2-29.1 26.9-23 45.9 5 14.5 19.1 24 33.6 24.6 6.8 1 12-1.6 57.7-16.8l15.7 47c5.9 16.9 24.7 29 46 22.9 19.1-6.2 29.1-26.8 23-45.9l-15.7-47 93.6-31.3 15.7 47c5.9 16.9 24.7 29 46 22.9 19.1-6.2 29.1-26.8 23-45.9l-15.7-47 45.4-15.1c19-6 29.1-26.7 22.9-45.7zm-254.1 47.2l-30.3-90.2 93.5-31.3 30.3 90.2-93.5 31.3z"},null,-1),oe={key:27,viewBox:"0 0 512 512"},ae=(0,l.createVNode)("path",{fill:"currentColor",d:"M448 192V77.25c0-8.49-3.37-16.62-9.37-22.63L393.37 9.37c-6-6-14.14-9.37-22.63-9.37H96C78.33 0 64 14.33 64 32v160c-35.35 0-64 28.65-64 64v112c0 8.84 7.16 16 16 16h48v96c0 17.67 14.33 32 32 32h320c17.67 0 32-14.33 32-32v-96h48c8.84 0 16-7.16 16-16V256c0-35.35-28.65-64-64-64zm-64 256H128v-96h256v96zm0-224H128V64h192v48c0 8.84 7.16 16 16 16h48v96zm48 72c-13.25 0-24-10.75-24-24 0-13.26 10.75-24 24-24s24 10.74 24 24c0 13.25-10.75 24-24 24z"},null,-1);const re={props:{name:{type:String,required:!0}},render:function(e,c,t,re,ne,se){return"double-down"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",o,[a])):"patient"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",r,[n])):"clinic"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",s,[i])):"procedure"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",m,[d])):"double-right"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",u,[p])):"times"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",h,[k])):"times-circle"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",v,[f])):"info-circle"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",B,[g])):"clipboard-list"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",V,[y])):"ambulance"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",z,[C])):"camera"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",b,[N])):"image"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",w,[x])):"circle-notch"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",H,[M])):"exclamation-circle"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",L,[O])):"trash-alt"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",S,[I])):"edit"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",j,[D])):"readme"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",A,[Z])):"eyes"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",q,[F])):"eyes-slash"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",P,[T])):"check-circle"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",U,[$])):"eraser"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",_,[E])):"notes-medical"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",J,[G])):"phone-square"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",K,[Q])):"filter"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",R,[W])):"file-excel"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",X,[Y])):"folder-open"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",ee,[ce])):"slack-hash"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",te,[le])):"print"===t.name?((0,l.openBlock)(),(0,l.createBlock)("svg",oe,[ae])):(0,l.createCommentVNode)("",!0)}},ne=re},6491:(e,c,t)=>{"use strict";t.r(c),t.d(c,{default:()=>b});var l=t(5166),o={class:"bg-white rounded shadow-sm p-4 mt-8"},a={class:"font-semibold pb-2 border-b-2 border-dashed text-thick-theme-light text-xl flex justify-center items-baseline"},r=(0,l.createVNode)("p",null,"ใบส่งตัว",-1),n={key:0,class:"ml-6 text-sm font-normal"},s=(0,l.createTextVNode)(" พิมพ์ "),i=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-6"}," ข้อมูลเบื้องต้น ",-1),m={class:"mt-2 sm:grid grid-rows-6 xl:grid-rows-4 grid-flow-col gap-2 lg:gap-3 xl:gap-4"},d=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"}," Vital Signs ล่าสุด ",-1),u={class:"mt-2 grid grid-rows-4 sm:grid-rows-3 grid-flow-col gap-1 sm:gap-2 lg:gap-3 xl:gap-4"},p={class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"},h={class:"mt-2"},k=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"}," คำสั่งการรักษา ",-1),v=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"}," เพิ่มเติม ",-1),f=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"}," เอกสารแนบ ",-1),B=(0,l.createVNode)("h3",{class:"font-normal underline text-dark-theme-light mt-8 md:mt-12"}," ผู้เขียน ",-1);var g=t(4538),V=t(4152),y=t(7130),z=t(4643);const C={components:{DisplayInput:g.Z,ContactCard:V.Z,ImageUploader:y.Z,Icon:z.Z},props:{contents:{type:Object,required:!0},configs:{type:Object,required:!0}},computed:{filteredTreatments:function(){var e=this,c=[];return Object.keys(this.contents.treatments).forEach((function(t){var l=e.configs.treatments.findIndex((function(e){return e.name===t}));-1!==l&&c.push(e.configs.treatments[l])})),c}},data:function(){return{uploads:this.contents.uploads}},render:function(e,c,t,g,V,y){var z=(0,l.resolveComponent)("icon"),C=(0,l.resolveComponent)("display-input"),b=(0,l.resolveComponent)("image-uploader"),N=(0,l.resolveComponent)("contact-card");return(0,l.openBlock)(),(0,l.createBlock)("div",null,[(0,l.createVNode)("div",o,[(0,l.createVNode)("h2",a,[r,t.contents.submitted?((0,l.openBlock)(),(0,l.createBlock)("a",{key:1,href:"".concat(e.$page.props.app.baseUrl,"/printouts/").concat(t.configs.note_slug),class:"ml-6 text-sm font-normal text-dark-theme-light flex",target:"_blank"},[(0,l.createVNode)(z,{class:"mr-2 h-4 w-4",name:"print"}),s],8,["href"])):((0,l.openBlock)(),(0,l.createBlock)("p",n," (ยังเขียนไม่เสร็จ) "))]),i,(0,l.createVNode)("div",m,[((0,l.openBlock)(!0),(0,l.createBlock)(l.Fragment,null,(0,l.renderList)(t.configs.patient,(function(e,c){var o;return(0,l.openBlock)(),(0,l.createBlock)(C,{class:"mt-2 md:mt-0",key:c,label:e.label,data:t.contents.patient[e.name],format:null!==(o=e.format)&&void 0!==o?o:""},null,8,["label","data","format"])})),128))]),d,(0,l.createVNode)("div",u,[((0,l.openBlock)(!0),(0,l.createBlock)(l.Fragment,null,(0,l.renderList)(t.configs.vital_signs,(function(e,c){return(0,l.openBlock)(),(0,l.createBlock)(C,{class:"mt-2 md:mt-0",key:c,label:e.label,data:t.contents.vital_signs[e.name]},null,8,["label","data"])})),128))]),((0,l.openBlock)(!0),(0,l.createBlock)(l.Fragment,null,(0,l.renderList)(t.configs.topics,(function(e,c){return(0,l.openBlock)(),(0,l.createBlock)(l.Fragment,{key:c},[(0,l.createVNode)("h3",p,(0,l.toDisplayString)(e.label),1),(0,l.createVNode)("div",h,[(0,l.createVNode)(C,{class:"mt-2 md:mt-0",data:t.contents[e.name]},null,8,["data"])])],64)})),128)),k,(0,l.createVNode)("div",{class:["mt-2 grid grid-flow-col gap-2 lg:gap-3 xl:gap-4",{"grid-rows-1":y.filteredTreatments.length<=2,"grid-rows-2 sm:grid-rows-1":3===y.filteredTreatments.length,"grid-rows-3 sm:grid-rows-2":y.filteredTreatments.length>3}]},[((0,l.openBlock)(!0),(0,l.createBlock)(l.Fragment,null,(0,l.renderList)(y.filteredTreatments,(function(e,c){return(0,l.openBlock)(),(0,l.createBlock)(C,{class:"mt-2 md:mt-0",key:c,label:e.label,data:t.contents.treatments[e.name]},null,8,["label","data"])})),128))],2),t.contents.remark?((0,l.openBlock)(),(0,l.createBlock)(l.Fragment,{key:0},[v,(0,l.createVNode)(C,{class:"mt-2 md:mt-0",data:t.contents.remark},null,8,["data"])],64)):(0,l.createCommentVNode)("",!0),f,"ศิริราช"!==t.contents.patient.center?((0,l.openBlock)(),(0,l.createBlock)(b,{key:1,class:"mt-2",label:"๏  Film Chest ล่าสุด",name:"contents->uploads->film","note-id":0,modelValue:V.uploads.film,"onUpdate:modelValue":c[1]||(c[1]=function(e){return V.uploads.film=e}),readonly:!0},null,8,["modelValue"])):(0,l.createCommentVNode)("",!0),(0,l.createVNode)(b,{class:"mt-2",label:"๏  ใบรายงานผล COVID",name:"contents->uploads->lab","note-id":0,modelValue:V.uploads.lab,"onUpdate:modelValue":c[2]||(c[2]=function(e){return V.uploads.lab=e}),readonly:!0},null,8,["modelValue"]),B,(0,l.createVNode)(N,{contact:t.contents.author},null,8,["contact"])])])}},b=C}}]);