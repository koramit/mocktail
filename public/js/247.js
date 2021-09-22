"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[247],{3744:(e,t)=>{t.Z=(e,t)=>{for(const[o,l]of t)e[o]=l;return e}},8369:(e,t,o)=>{o.d(t,{Z:()=>f});var l=o(4359),r={key:0},n={class:"inline-flex items-center cursor-pointer"},a={class:"relative"},c={class:"ml-3 text-sm md:text-base xl:text-lg"},i={key:1},s={class:"inline-flex items-center cursor-pointer"},d={class:"text-bitter-theme-light"},m=["checked","disabled"],u={class:"ml-4 text-sm md:text-base xl:text-lg"};const p={emits:["update:modelValue","autosave"],props:{modelValue:{type:Boolean},label:{type:String,default:""},toggler:{type:Boolean},disabled:{type:Boolean}},methods:{change:function(){this.$emit("update:modelValue",!this.modelValue),this.$emit("autosave")},check:function(){this.$emit("update:modelValue",!this.modelValue)}}};const f=(0,o(3744).Z)(p,[["render",function(e,t,o,p,f,h){return o.toggler?((0,l.openBlock)(),(0,l.createElementBlock)("div",r,[(0,l.createElementVNode)("label",n,[(0,l.createElementVNode)("div",a,[(0,l.createElementVNode)("input",{type:"checkbox",class:"hidden",onChange:t[0]||(t[0]=function(){return h.change&&h.change.apply(h,arguments)})},null,32),(0,l.createElementVNode)("div",{class:(0,l.normalizeClass)(["w-8 h-5 bg-gray-200 rounded-full shadow-inner transition-all duration-200 ease-in-out",{"bg-bitter-theme-light":o.modelValue}])},null,2),(0,l.createElementVNode)("div",{class:(0,l.normalizeClass)(["absolute w-5 h-5 bg-white rounded-full shadow inset-y-0 left-0 transition-all duration-200 ease-in-out transform",{"translate-x-3":o.modelValue}])},null,2)]),(0,l.createElementVNode)("div",c,(0,l.toDisplayString)(o.label),1)])])):((0,l.openBlock)(),(0,l.createElementBlock)("div",i,[(0,l.createElementVNode)("label",s,[(0,l.createElementVNode)("span",d,[(0,l.createElementVNode)("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-dark-theme-light select-none flex-shrink-0 rounded cursor-pointer focus:outline-none",checked:o.modelValue,onChange:t[1]||(t[1]=function(){return h.change&&h.change.apply(h,arguments)}),disabled:o.disabled},null,40,m)]),(0,l.createElementVNode)("span",u,(0,l.toDisplayString)(o.label),1)])]))}]])},4233:(e,t,o)=>{o.d(t,{Z:()=>f});var l=o(4359),r={class:"w-full"},n=["for"],a={key:1,class:"flex"},c=["id","name","type","placeholder","pattern","readonly","value"],i={class:"inline-flex items-center"},s=["checked"],d={class:"ml-2 text-sm md:ml-4 md:text-lg cursor-pointer whitespace-nowrap"},m=["id","name","type","placeholder","pattern","readonly","value"],u={key:3,class:"text-red-700 mt-2 text-sm"};const p={emits:["autosave","update:modelValue","update:modelCheckbox"],props:{modelValue:{type:String,default:""},modelCheckbox:{type:Boolean},name:{type:String,required:!0},label:{type:String,default:""},type:{type:String,default:"text"},placeholder:{type:String,default:""},pattern:{type:String,default:""},readonly:{type:Boolean},error:{type:String,default:""},switchLabel:{type:String,default:""}},methods:{focus:function(){this.$refs.input.focus()},change:function(){this.$emit("update:modelCheckbox",!this.modelCheckbox),this.$emit("autosave")}}};const f=(0,o(3744).Z)(p,[["render",function(e,t,o,p,f,h){return(0,l.openBlock)(),(0,l.createElementBlock)("div",r,[o.label?((0,l.openBlock)(),(0,l.createElementBlock)("label",{key:0,class:"form-label",for:o.name},(0,l.toDisplayString)(o.label)+" :",9,n)):(0,l.createCommentVNode)("",!0),o.switchLabel?((0,l.openBlock)(),(0,l.createElementBlock)("div",a,[(0,l.createElementVNode)("input",{id:o.name,name:o.name,ref:"input",onInput:t[0]||(t[0]=function(t){return e.$emit("update:modelValue",e.$refs.input.value)}),onChange:t[1]||(t[1]=function(t){return e.$emit("autosave")}),type:o.type,placeholder:o.placeholder,pattern:o.pattern,readonly:o.readonly,value:o.modelValue,class:(0,l.normalizeClass)(["form-input border-r-0 rounded-r-none",{"border-red-400":o.error}])},null,42,c),(0,l.createElementVNode)("div",{class:(0,l.normalizeClass)(["w-auto flex items-center px-2 border-2 border-gray-200 rounded shadow-sm border-l-0 rounded-l-none bg-gray-50",{"border-red-400":o.error}])},[(0,l.createElementVNode)("label",i,[(0,l.createElementVNode)("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-gray-400 select-none flex-shrink-0 rounded cursor-pointer focus:outline-none",checked:o.modelCheckbox,onChange:t[2]||(t[2]=function(){return h.change&&h.change.apply(h,arguments)})},null,40,s),(0,l.createElementVNode)("span",d,(0,l.toDisplayString)(o.switchLabel),1)])],2)])):((0,l.openBlock)(),(0,l.createElementBlock)("input",{key:2,id:o.name,name:o.name,ref:"input",onInput:t[3]||(t[3]=function(t){return e.$emit("update:modelValue",e.$refs.input.value)}),onChange:t[4]||(t[4]=function(t){return e.$emit("autosave")}),type:o.type,placeholder:o.placeholder,pattern:o.pattern,readonly:o.readonly,value:o.modelValue,class:(0,l.normalizeClass)(["form-input",{"border-red-400 text-red-400":o.error}])},null,42,m)),o.error?((0,l.openBlock)(),(0,l.createElementBlock)("div",u,(0,l.toDisplayString)(o.error),1)):(0,l.createCommentVNode)("",!0)])}]])},6119:(e,t,o)=>{o.d(t,{Z:()=>y});var l=o(4359),r={class:"w-full"},n=["for"],a={class:"relative"},c=["id","name","placeholder","disabled","value"],i=(0,l.createElementVNode)("option",{disabled:"",value:""}," โปรดเลือก ",-1),s={key:0,class:"italic text-yellow-500"},d=["value"],m=["value"],u={key:1,value:"other"},p=(0,l.createElementVNode)("div",{class:"pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700"},[(0,l.createElementVNode)("svg",{class:"fill-current h-4 w-4",xmlns:"http://www.w3.org/2000/svg",viewBox:"0 0 20 20"},[(0,l.createElementVNode)("path",{d:"M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"})])],-1),f={key:1,class:"text-red-700 mt-2 text-sm"};function h(e){return h="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},h(e)}const b={emits:["update:modelValue","autosave"],props:{modelValue:{type:String,default:""},options:{type:Array,required:!0},name:{type:String,required:!0},label:{type:String,default:""},placeholder:{type:String,default:""},disabled:{type:Boolean},error:{type:String,default:""},allowOther:{type:Boolean}},computed:{valueOptions:function(){return"object"===h(this.options[0])?[]:this.options},itemOptions:function(){return"object"===h(this.options[0])?this.options:[]}},watch:{modelValue:function(e){"ยกเลิก"===e&&(this.$emit("update:modelValue",""),this.$emit("autosave"))}},methods:{change:function(){this.$emit("update:modelValue",this.$refs.input.value),this.$emit("autosave")},setOther:function(e){var t=this;this.$nextTick((function(){t.$refs.input.value=e,t.change()}))}}};const y=(0,o(3744).Z)(b,[["render",function(e,t,o,h,b,y){return(0,l.openBlock)(),(0,l.createElementBlock)("div",r,[o.label?((0,l.openBlock)(),(0,l.createElementBlock)("label",{key:0,class:"form-label",for:o.name},(0,l.toDisplayString)(o.label)+" :",9,n)):(0,l.createCommentVNode)("",!0),(0,l.createElementVNode)("div",a,[(0,l.createElementVNode)("select",{id:o.name,name:o.name,ref:"input",placeholder:o.placeholder,disabled:o.disabled,value:o.modelValue,onChange:t[0]||(t[0]=function(){return y.change&&y.change.apply(y,arguments)}),class:(0,l.normalizeClass)(["form-input cursor-pointer disabled:cursor-not-allowed",{"border-red-400":o.error,"bg-gray-400":o.disabled}])},[i,o.modelValue?((0,l.openBlock)(),(0,l.createElementBlock)("option",s," ยกเลิก ")):(0,l.createCommentVNode)("",!0),((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(y.itemOptions,(function(e,t){return(0,l.openBlock)(),(0,l.createElementBlock)("option",{key:t,value:e.value},(0,l.toDisplayString)(e.label),9,d)})),128)),((0,l.openBlock)(!0),(0,l.createElementBlock)(l.Fragment,null,(0,l.renderList)(y.valueOptions,(function(e,t){return(0,l.openBlock)(),(0,l.createElementBlock)("option",{key:t,value:e},(0,l.toDisplayString)(e),9,m)})),128)),o.allowOther?((0,l.openBlock)(),(0,l.createElementBlock)("option",u," อื่นๆ ")):(0,l.createCommentVNode)("",!0)],42,c),p]),o.error?((0,l.openBlock)(),(0,l.createElementBlock)("div",f,(0,l.toDisplayString)(o.error),1)):(0,l.createCommentVNode)("",!0)])}]])},3917:(e,t,o)=>{o.d(t,{Z:()=>i});var l=o(4359),r=["disabled"],n={key:0,class:"animate-spin -ml-1 mr-3 h-5 w-5 text-white",xmlns:"http://www.w3.org/2000/svg",fill:"none",viewBox:"0 0 24 24"},a=[(0,l.createElementVNode)("circle",{class:"opacity-25",cx:"12",cy:"12",r:"10",stroke:"currentColor","stroke-width":"4"},null,-1),(0,l.createElementVNode)("path",{class:"opacity-75",fill:"currentColor",d:"M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"},null,-1)];const c={emits:["click"],props:{disabled:{type:Boolean},spin:{type:Boolean}}};const i=(0,o(3744).Z)(c,[["render",function(e,t,o,c,i,s){return(0,l.openBlock)(),(0,l.createElementBlock)("button",{type:"button",class:"inline-flex justify-center items-center btn",onClick:t[0]||(t[0]=function(t){return e.$emit("click")}),disabled:o.disabled||o.spin},[o.spin?((0,l.openBlock)(),(0,l.createElementBlock)("svg",n,a)):(0,l.createCommentVNode)("",!0),(0,l.renderSlot)(e.$slots,"default")],8,r)}]])},2247:(e,t,o)=>{o.r(t),o.d(t,{default:()=>x});var l=o(4359),r={class:"flex flex-col justify-center items-center w-full min-h-screen my-6"},n=(0,l.createElementVNode)("div",{class:"flex flex-col justify-center items-center w-28 h-28 rounded-full border-soft-theme-light border-4 font-semibold bg-dark-theme-light z-10"},[(0,l.createElementVNode)("div",{class:"text-white text-3xl"}," SiMED "),(0,l.createElementVNode)("div",{class:"text-soft-theme-light text-xl italic"}," Mocktail ")],-1),a={class:"mt-4 px-4 py-8 w-80 lg:w-96 bg-white rounded shadow transform -translate-y-20"},c=(0,l.createElementVNode)("span",{class:"block font-semibold text-xl text-thick-theme-light mt-12 text-center"},"ลงทะเบียน",-1),i={key:0,class:"block font-semibold text-sm text-red-400 mt-6 text-center"},s={key:1,class:"mt-6"},d={key:2,class:"mt-4"},m=["href"],u=(0,l.createTextVNode)(" ลงทะเบียน ");var p=o(9038),f=o(8369),h=o(4233),b=o(6119),y=o(3917);function g(e,t){var o=Object.keys(e);if(Object.getOwnPropertySymbols){var l=Object.getOwnPropertySymbols(e);t&&(l=l.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),o.push.apply(o,l)}return o}function k(e){for(var t=1;t<arguments.length;t++){var o=null!=arguments[t]?arguments[t]:{};t%2?g(Object(o),!0).forEach((function(t){V(e,t,o[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(o)):g(Object(o)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(o,t))}))}return e}function V(e,t,o){return t in e?Object.defineProperty(e,t,{value:o,enumerable:!0,configurable:!0,writable:!0}):e[t]=o,e}const v={components:{FormCheckbox:f.Z,FormInput:h.Z,FormSelect:b.Z,SpinnerButton:y.Z,Head:p.Fb},props:{profile:{type:Object,required:!0}},data:function(){return{form:(0,p.cI)({name:null,full_name:null,tel_no:null,pln:null,center:null,email:null,password:null,agreement_accepted:!1,remember:!0}),baseUrl:this.$page.props.app.baseUrl}},created:function(){void 0!==this.profile.org_id&&(this.form.full_name=this.profile.name),this.baseUrl=document.querySelector("meta[name=base-url]").content;var e=Date.now(),t=this.baseUrl+"/session-timeout",o=parseInt(document.querySelector("meta[name=session-lifetime-seconds]").content);window.addEventListener("focus",(function(){Date.now()-e>o&&window.axios.post(t).then((function(){return e=Date.now()})).catch((function(){return location.reload()}))}))},mounted:function(){this.$nextTick((function(){var e=document.getElementById("page-loading-indicator");e&&e.remove()}))},methods:{login:function(){var e=this;this.form.transform((function(t){return k(k({},t),{},{login:void 0!==e.profile.org_id?e.profile.username:t.email,remember:t.remember?"on":""})})).post("".concat(this.baseUrl,"/register"),{onFinish:function(){return e.form.processing=!1}})}}};const x=(0,o(3744).Z)(v,[["render",function(e,t,o,p,f,h){var b=(0,l.resolveComponent)("Head"),y=(0,l.resolveComponent)("form-select"),g=(0,l.resolveComponent)("form-input"),k=(0,l.resolveComponent)("form-checkbox"),V=(0,l.resolveComponent)("spinner-button");return(0,l.openBlock)(),(0,l.createElementBlock)(l.Fragment,null,[(0,l.createVNode)(b,{title:"Mocktail: ลงทะเบียน"}),(0,l.createElementVNode)("div",r,[n,(0,l.createElementVNode)("div",a,[c,f.form.errors.login?((0,l.openBlock)(),(0,l.createElementBlock)("span",i,(0,l.toDisplayString)(void 0===o.profile.org_id?"อีเมล์นี้ถูกใช้เป็นชื่อบัญชีแล้ว โปรดเลือกใหม่":"ชื่อบัญชีนี้ถูกใช้แล้ว โปรดติดต่อผู้ดูแลระบบ"),1)):(0,l.createCommentVNode)("",!0),void 0===o.profile.org_id?((0,l.openBlock)(),(0,l.createElementBlock)("div",s,[(0,l.createVNode)(y,{class:"mt-2",label:"ศูนย์",modelValue:f.form.center,"onUpdate:modelValue":t[0]||(t[0]=function(e){return f.form.center=e}),name:"center",error:f.form.errors.center,options:["ศิริราช","ปิยมหาราชการุณย์","ศูนย์การแพทย์กาญจนาภิเษก"]},null,8,["modelValue","error"]),(0,l.createVNode)(g,{class:"mt-2",name:"email",type:"email",label:"อีเมล์",modelValue:f.form.email,"onUpdate:modelValue":t[1]||(t[1]=function(e){return f.form.email=e}),error:f.form.errors.email,placeholder:"ใช้เป็นชื่อบัญชีเข้าใช้งาน"},null,8,["modelValue","error"]),(0,l.createVNode)(g,{class:"mt-2",name:"password",type:"password",label:"รหัสผ่าน",modelValue:f.form.password,"onUpdate:modelValue":t[2]||(t[2]=function(e){return f.form.password=e}),error:f.form.errors.password},null,8,["modelValue","error"])])):((0,l.openBlock)(),(0,l.createElementBlock)("div",d)),(0,l.createVNode)(g,{class:"mt-2",name:"name",label:"นามแสดง",modelValue:f.form.name,"onUpdate:modelValue":t[3]||(t[3]=function(e){return f.form.name=e}),error:f.form.errors.name,placeholder:"ชื่อที่ใช้แสดงในระบบ (ชื่อเล่น นามแฝง)"},null,8,["modelValue","error"]),(0,l.createVNode)(g,{class:"mt-2",name:"full_name",label:"ชื่อเต็ม",placeholder:"คำนำหน้า ชื่อ สกุล",modelValue:f.form.full_name,"onUpdate:modelValue":t[4]||(t[4]=function(e){return f.form.full_name=e}),error:f.form.errors.full_name,readonly:void 0!==o.profile.org_id},null,8,["modelValue","error","readonly"]),(0,l.createVNode)(g,{class:"mt-2",type:"tel",name:"tel_no",label:"หมายเลขโทรศัพท์ที่สามารถติดต่อได้",modelValue:f.form.tel_no,"onUpdate:modelValue":t[5]||(t[5]=function(e){return f.form.tel_no=e}),error:f.form.errors.tel_no,placeholder:"ใช้เพื่อติดต่อเมื่อจำเป็นเท่านั้น"},null,8,["modelValue","error"]),(0,l.createVNode)(g,{class:"mt-2",type:"tel",name:"pln",label:"เลข ว. (กรณีแพทย์)",modelValue:f.form.pln,"onUpdate:modelValue":t[6]||(t[6]=function(e){return f.form.pln=e}),error:f.form.errors.pln,placeholder:"ใช้พิมพ์ออกมาพร้อมเอกสารเวชระเบียน"},null,8,["modelValue","error"]),(0,l.createVNode)(k,{class:"mt-2",modelValue:f.form.agreement_accepted,"onUpdate:modelValue":t[7]||(t[7]=function(e){return f.form.agreement_accepted=e}),label:"ยอมรับนโยบายความเป็นส่วนตัวและข้อตกลงการใช้งาน",toggler:!0},null,8,["modelValue"]),(0,l.createElementVNode)("a",{href:"".concat(f.baseUrl,"/terms-and-policies"),class:"mt-2 block text-bitter-theme-light",target:"_blank"},"อ่านนโยบายและข้อตกลง",8,m),(0,l.createVNode)(V,{spin:f.form.processing,class:"btn-dark w-full mt-4",onClick:h.login,disabled:!f.form.agreement_accepted||void 0===o.profile.org_id&&""===f.form.center},{default:(0,l.withCtx)((function(){return[u]})),_:1},8,["spin","onClick","disabled"])])])],64)}]])}}]);