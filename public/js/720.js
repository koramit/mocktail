(self.webpackChunk=self.webpackChunk||[]).push([[720],{8369:(e,t,o)=>{"use strict";o.d(t,{Z:()=>p});var r=o(5166),l={key:0},n={class:"inline-flex items-center cursor-pointer"},a={class:"relative"},c={class:"ml-3 text-sm md:text-base xl:text-lg"},i={key:1},s={class:"inline-flex items-center cursor-pointer "},d={class:"text-bitter-theme-light"},u={class:"ml-4 text-sm md:text-base xl:text-lg"};const m={emits:["update:modelValue","autosave"],props:{modelValue:{type:Boolean},label:{type:String,default:""},toggler:{type:Boolean},disabled:{type:Boolean}},methods:{change:function(){this.$emit("update:modelValue",!this.modelValue),this.$emit("autosave")},check:function(){this.$emit("update:modelValue",!this.modelValue)}},render:function(e,t,o,m,p,f){return o.toggler?((0,r.openBlock)(),(0,r.createBlock)("div",l,[(0,r.createVNode)("label",n,[(0,r.createVNode)("div",a,[(0,r.createVNode)("input",{type:"checkbox",class:"hidden",onChange:t[1]||(t[1]=function(){return f.change&&f.change.apply(f,arguments)})},null,32),(0,r.createVNode)("div",{class:["w-8 h-5 bg-gray-200 rounded-full shadow-inner transition-all duration-200 ease-in-out",{"bg-bitter-theme-light":o.modelValue}]},null,2),(0,r.createVNode)("div",{class:["absolute w-5 h-5 bg-white rounded-full shadow inset-y-0 left-0 transition-all duration-200 ease-in-out transform",{"translate-x-3":o.modelValue}]},null,2)]),(0,r.createVNode)("div",c,(0,r.toDisplayString)(o.label),1)])])):((0,r.openBlock)(),(0,r.createBlock)("div",i,[(0,r.createVNode)("label",s,[(0,r.createVNode)("span",d,[(0,r.createVNode)("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-dark-theme-light select-none flex-shrink-0 rounded cursor-pointer focus:outline-none",checked:o.modelValue,onChange:t[2]||(t[2]=function(){return f.change&&f.change.apply(f,arguments)}),disabled:o.disabled},null,40,["checked","disabled"])]),(0,r.createVNode)("span",u,(0,r.toDisplayString)(o.label),1)])]))}},p=m},6264:(e,t,o)=>{"use strict";o.d(t,{Z:()=>u});var r=o(5166),l={class:"w-full"},n={key:1,class:"flex"},a={class:"w-auto flex items-center px-2 border-2 border-gray-200 rounded shadow-sm border-l-0 rounded-l-none bg-gray-50"},c={class:"inline-flex items-center"},i={class:"ml-4 text-lg cursor-pointer whitespace-nowrap"},s={key:3,class:"text-red-700 mt-2 text-sm"};const d={emits:["autosave","update:modelValue","update:modelCheckbox"],props:{modelValue:{type:String,required:!0},modelCheckbox:{type:Boolean},name:{type:String,required:!0},label:{type:String,default:""},type:{type:String,default:"text"},placeholder:{type:String,default:""},pattern:{type:String,default:""},readonly:{type:Boolean},error:{type:String,default:""},switchLabel:{type:String,default:""}},methods:{focus:function(){this.$refs.input.focus()},change:function(){this.$emit("update:modelCheckbox",!this.modelCheckbox),this.$emit("autosave")}},render:function(e,t,o,d,u,m){return(0,r.openBlock)(),(0,r.createBlock)("div",l,[o.label?((0,r.openBlock)(),(0,r.createBlock)("label",{key:0,class:"form-label",for:o.name},(0,r.toDisplayString)(o.label)+" :",9,["for"])):(0,r.createCommentVNode)("",!0),o.switchLabel?((0,r.openBlock)(),(0,r.createBlock)("div",n,[(0,r.createVNode)("input",{id:o.name,name:o.name,ref:"input",onInput:t[1]||(t[1]=function(t){return e.$emit("update:modelValue",e.$refs.input.value)}),onChange:t[2]||(t[2]=function(t){return e.$emit("autosave")}),type:o.type,placeholder:o.placeholder,pattern:o.pattern,readonly:o.readonly,value:o.modelValue,class:["form-input border-r-0 rounded-r-none",{"border-red-400":o.error}]},null,42,["id","name","type","placeholder","pattern","readonly","value"]),(0,r.createVNode)("div",a,[(0,r.createVNode)("label",c,[(0,r.createVNode)("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none flex-shrink-0 rounded cursor-pointer focus:outline-none",checked:o.modelCheckbox,onChange:t[3]||(t[3]=function(){return m.change&&m.change.apply(m,arguments)})},null,40,["checked"]),(0,r.createVNode)("span",i,(0,r.toDisplayString)(o.switchLabel),1)])])])):((0,r.openBlock)(),(0,r.createBlock)("input",{key:2,id:o.name,name:o.name,ref:"input",onInput:t[4]||(t[4]=function(t){return e.$emit("update:modelValue",e.$refs.input.value)}),onChange:t[5]||(t[5]=function(t){return e.$emit("autosave")}),type:o.type,placeholder:o.placeholder,pattern:o.pattern,readonly:o.readonly,value:o.modelValue,class:["form-input",{"border-red-400 text-red-400":o.error}]},null,42,["id","name","type","placeholder","pattern","readonly","value"])),o.error?((0,r.openBlock)(),(0,r.createBlock)("div",s,(0,r.toDisplayString)(o.error),1)):(0,r.createCommentVNode)("",!0)])}},u=d},3804:(e,t,o)=>{"use strict";o.d(t,{Z:()=>p});var r=o(5166),l={class:"w-full"},n={class:"relative"},a=(0,r.createVNode)("option",{disabled:"",value:""}," โปรดเลือก ",-1),c={key:0,class:"italic text-yellow-500"},i={key:1,value:"other"},s=(0,r.createVNode)("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"},[(0,r.createVNode)("svg",{class:"fill-current h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"},[(0,r.createVNode)("path",{d:"M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"})])],-1),d={key:1,class:"text-red-700 mt-2 text-sm"};function u(e){return(u="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e})(e)}const m={emits:["update:modelValue","autosave"],props:{modelValue:{type:String,required:!0},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},placeholder:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowOther:{type:Boolean}},computed:{valueOptions:function(){return"object"===u(this.options[0])?[]:this.options},itemOptions:function(){return"object"===u(this.options[0])?this.options:[]}},watch:{modelValue:function(e){"Remove"===e&&(this.$emit("update:modelValue",""),this.$emit("autosave"))}},methods:{change:function(){this.$emit("update:modelValue",this.$refs.input.value),this.$emit("autosave")},setOther:function(e){var t=this;this.$nextTick((function(){t.$refs.input.value=e,t.change()}))}},render:function(e,t,o,u,m,p){return(0,r.openBlock)(),(0,r.createBlock)("div",l,[o.label?((0,r.openBlock)(),(0,r.createBlock)("label",{key:0,class:"form-label",for:o.name},(0,r.toDisplayString)(o.label)+" :",9,["for"])):(0,r.createCommentVNode)("",!0),(0,r.createVNode)("div",n,[(0,r.createVNode)("select",{id:o.name,name:o.name,ref:"input",placeholder:o.placeholder,disabled:o.disabled,value:o.modelValue,onChange:t[1]||(t[1]=function(){return p.change&&p.change.apply(p,arguments)}),class:["form-input cursor-pointer disabled:cursor-not-allowed",{"border-red-400":o.error,"bg-gray-400":o.disabled}]},[a,o.modelValue?((0,r.openBlock)(),(0,r.createBlock)("option",c," ยกเลิก ")):(0,r.createCommentVNode)("",!0),((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(p.itemOptions,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)("option",{key:t,value:e.value},(0,r.toDisplayString)(e.label),9,["value"])})),128)),((0,r.openBlock)(!0),(0,r.createBlock)(r.Fragment,null,(0,r.renderList)(p.valueOptions,(function(e,t){return(0,r.openBlock)(),(0,r.createBlock)("option",{key:t,value:e},(0,r.toDisplayString)(e),9,["value"])})),128)),o.allowOther?((0,r.openBlock)(),(0,r.createBlock)("option",i," อื่นๆ ")):(0,r.createCommentVNode)("",!0)],42,["id","name","placeholder","disabled","value"]),s]),o.error?((0,r.openBlock)(),(0,r.createBlock)("div",d,(0,r.toDisplayString)(o.error),1)):(0,r.createCommentVNode)("",!0)])}},p=m},3917:(e,t,o)=>{"use strict";o.d(t,{Z:()=>i});var r=o(5166),l={key:0,class:"animate-spin -ml-1 mr-3 h-5 w-5 text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24"},n=(0,r.createVNode)("circle",{class:"opacity-25",cx:"12",cy:"12",r:"10",stroke:"currentColor","stroke-width":"4"},null,-1),a=(0,r.createVNode)("path",{class:"opacity-75",fill:"currentColor",d:"M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"},null,-1);const c={emits:["click"],props:{disabled:{type:Boolean},spin:{type:Boolean}},render:function(e,t,o,c,i,s){return(0,r.openBlock)(),(0,r.createBlock)("button",{type:"button",class:"inline-flex justify-center items-center btn",onClick:t[1]||(t[1]=function(t){return e.$emit("click")}),disabled:o.disabled||o.spin},[o.spin?((0,r.openBlock)(),(0,r.createBlock)("svg",l,[n,a])):(0,r.createCommentVNode)("",!0),(0,r.renderSlot)(e.$slots,"default")],8,["disabled"])}},i=c},7720:(e,t,o)=>{"use strict";o.r(t),o.d(t,{default:()=>v});var r=o(5166),l={class:"flex flex-col justify-center items-center w-full min-h-screen my-6"},n=(0,r.createVNode)("div",{class:"flex flex-col justify-center items-center w-28 h-28 rounded-full border-soft-theme-light border-4 font-semibold bg-dark-theme-light z-10"},[(0,r.createVNode)("div",{class:" text-white text-3xl"}," SiMED "),(0,r.createVNode)("div",{class:" text-soft-theme-light text-xl italic"}," Mocktail ")],-1),a={class:"mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow transform -translate-y-20"},c=(0,r.createVNode)("span",{class:"block font-semibold text-xl text-thick-theme-light mt-12 text-center"},"ลงทะเบียน",-1),i={key:0,class:"block font-semibold text-sm text-red-400 mt-6 text-center"},s={key:1,class:"mt-6"},d={key:2,class:"mt-4"},u=(0,r.createTextVNode)(" ลงทะเบียน ");var m=o(9038),p=o(8369),f=o(6264),h=o(3804),b=o(3917);function y(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,r)}return o}function g(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?y(Object(o),!0).forEach((function(t){k(e,t,o[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):y(Object(o)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(o,t))}))}return e}function k(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}const V={components:{FormCheckbox:p.Z,FormInput:f.Z,FormSelect:h.Z,SpinnerButton:b.Z},props:{profile:{type:Object,required:!0}},data:function(){return{form:(0,m.cI)({name:null,full_name:null,tel_no:null,pln:null,center:null,email:null,password:null,agreement_accepted:!1,remember:!0}),baseUrl:this.$page.props.app.baseUrl}},created:function(){document.title="Register",void 0!==this.profile.org_id&&(this.form.full_name=this.profile.name)},mounted:function(){this.$nextTick((function(){var e=document.getElementById("page-loading-indicator");e&&e.remove()}))},methods:{login:function(){var e=this;this.form.transform((function(t){return g(g({},t),{},{login:void 0!==e.profile.org_id?e.profile.username:t.email,remember:t.remember?"on":""})})).post("".concat(this.baseUrl,"/register"),{onFinish:function(){return e.form.processing=!1}})}},render:function(e,t,o,m,p,f){var h=(0,r.resolveComponent)("form-select"),b=(0,r.resolveComponent)("form-input"),y=(0,r.resolveComponent)("form-checkbox"),g=(0,r.resolveComponent)("spinner-button");return(0,r.openBlock)(),(0,r.createBlock)("div",l,[n,(0,r.createVNode)("div",a,[c,p.form.errors.login?((0,r.openBlock)(),(0,r.createBlock)("span",i,(0,r.toDisplayString)(void 0===o.profile.org_id?"อีเมล์นี้ถูกใช้เป็นชื่อบัญชีแล้ว โปรดเลือกใหม่":"ชื่อบัญชีนี้ถูกใช้แล้ว โปรดติดต่อผู้ดูแลระบบ"),1)):(0,r.createCommentVNode)("",!0),void 0===o.profile.org_id?((0,r.openBlock)(),(0,r.createBlock)("div",s,[(0,r.createVNode)(h,{class:"mt-2",label:"ศูนย์",modelValue:p.form.center,"onUpdate:modelValue":t[1]||(t[1]=function(e){return p.form.center=e}),name:"center",error:p.form.errors.center,options:["ปิยมหาราชการุณย์","ศูนย์การแพทย์กาญจนาภิเษก"]},null,8,["modelValue","error"]),(0,r.createVNode)(b,{class:"mt-2",name:"email",tyoe:"email",label:"อีเมล์",modelValue:p.form.email,"onUpdate:modelValue":t[2]||(t[2]=function(e){return p.form.email=e}),error:p.form.errors.email,placeholder:"ใช้เป็นชื่อบัญชีเข้าใช้งาน"},null,8,["modelValue","error"]),(0,r.createVNode)(b,{class:"mt-2",name:"password",type:"password",label:"รหัสผ่าน",modelValue:p.form.password,"onUpdate:modelValue":t[3]||(t[3]=function(e){return p.form.password=e}),error:p.form.errors.password},null,8,["modelValue","error"])])):((0,r.openBlock)(),(0,r.createBlock)("div",d)),(0,r.createVNode)(b,{class:"mt-2",name:"name",label:"นามแสดง",modelValue:p.form.name,"onUpdate:modelValue":t[4]||(t[4]=function(e){return p.form.name=e}),error:p.form.errors.name,placeholder:"ชื่อที่ใช้แสดงในระบบ (ชื่อเล่น นามแฝง)"},null,8,["modelValue","error"]),(0,r.createVNode)(b,{class:"mt-2",name:"full_name",label:"ชื่อเต็ม",placeholder:"คำนำหน้า ชื่อ สกุล",modelValue:p.form.full_name,"onUpdate:modelValue":t[5]||(t[5]=function(e){return p.form.full_name=e}),error:p.form.errors.full_name,readonly:void 0!==o.profile.org_id},null,8,["modelValue","error","readonly"]),(0,r.createVNode)(b,{class:"mt-2",type:"tel",name:"tel_no",label:"หมายเลขโทรศัพท์ที่สามารถติดต่อได้",modelValue:p.form.tel_no,"onUpdate:modelValue":t[6]||(t[6]=function(e){return p.form.tel_no=e}),error:p.form.errors.tel_no,placeholder:"ใช้เพื่อติดต่อเมื่อจำเป็นเท่านั้น"},null,8,["modelValue","error"]),(0,r.createVNode)(b,{class:"mt-2",type:"tel",name:"pln",label:"เลข ว. (กรณีแพทย์)",modelValue:p.form.pln,"onUpdate:modelValue":t[7]||(t[7]=function(e){return p.form.pln=e}),error:p.form.errors.pln,placeholder:"ใช้พิมพ์ออกมาพร้อมเอกสารเวชระเบียน"},null,8,["modelValue","error"]),(0,r.createVNode)(y,{class:"mt-2",modelValue:p.form.agreement_accepted,"onUpdate:modelValue":t[8]||(t[8]=function(e){return p.form.agreement_accepted=e}),label:"ยอมรับนโยบายความเป็นส่วนตัวและข้อตกลงการใช้งาน",toggler:!0},null,8,["modelValue"]),(0,r.createVNode)("a",{href:"".concat(p.baseUrl,"/terms-and-policies"),class:"mt-2 block text-bitter-theme-light",target:"_blank"},"อ่านนโยบายและข้อตกลง",8,["href"]),(0,r.createVNode)(g,{spin:p.form.processing,class:"btn-dark w-full mt-4",onClick:f.login,disabled:!p.form.agreement_accepted||void 0===o.profile.org_id&&""===p.form.center},{default:(0,r.withCtx)((function(){return[u]})),_:1},8,["spin","onClick","disabled"])])])}},v=V}}]);