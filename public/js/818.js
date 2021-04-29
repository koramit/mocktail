(self.webpackChunk=self.webpackChunk||[]).push([[818],{8369:(e,t,a)=>{"use strict";a.d(t,{Z:()=>p});var l=a(5166),o={key:0},n={class:"inline-flex items-center cursor-pointer"},r={class:"relative"},i={class:"ml-3 text-sm md:text-base xl:text-lg"},d={key:1},c={class:"inline-flex items-center cursor-pointer "},s={class:"text-bitter-theme-light"},u={class:"ml-4 text-sm md:text-base xl:text-lg"};const m={emits:["update:modelValue","autosave"],props:{modelValue:{type:Boolean},label:{type:String,default:""},toggler:{type:Boolean},disabled:{type:Boolean}},methods:{change:function(){this.$emit("update:modelValue",!this.modelValue),this.$emit("autosave")},check:function(){this.$emit("update:modelValue",!this.modelValue)}},render:function(e,t,a,m,p,h){return a.toggler?((0,l.openBlock)(),(0,l.createBlock)("div",o,[(0,l.createVNode)("label",n,[(0,l.createVNode)("div",r,[(0,l.createVNode)("input",{type:"checkbox",class:"hidden",onChange:t[1]||(t[1]=function(){return h.change&&h.change.apply(h,arguments)})},null,32),(0,l.createVNode)("div",{class:["w-8 h-5 bg-gray-200 rounded-full shadow-inner transition-all duration-200 ease-in-out",{"bg-bitter-theme-light":a.modelValue}]},null,2),(0,l.createVNode)("div",{class:["absolute w-5 h-5 bg-white rounded-full shadow inset-y-0 left-0 transition-all duration-200 ease-in-out transform",{"translate-x-3":a.modelValue}]},null,2)]),(0,l.createVNode)("div",i,(0,l.toDisplayString)(a.label),1)])])):((0,l.openBlock)(),(0,l.createBlock)("div",d,[(0,l.createVNode)("label",c,[(0,l.createVNode)("span",s,[(0,l.createVNode)("input",{type:"checkbox",class:"shadow-xs h-6 w-6 transition-all duration-200 ease-in-out appearance-none color inline-block align-middle border border-dark-theme-light select-none flex-shrink-0 rounded cursor-pointer focus:outline-none",checked:a.modelValue,onChange:t[2]||(t[2]=function(){return h.change&&h.change.apply(h,arguments)}),disabled:a.disabled},null,40,["checked","disabled"])]),(0,l.createVNode)("span",u,(0,l.toDisplayString)(a.label),1)])]))}},p=m},5818:(e,t,a)=>{"use strict";a.r(t),a.d(t,{default:()=>d});var l=a(5166),o={class:"p-4"},n=(0,l.createVNode)("h1",{class:"font-semibold text-lg underline text-center"}," เกณฑ์การรับผู้ป่วยเข้าในโรงพยาบาลสนามใบหยก (hospitel) ",-1),r={class:"bg-white rounded shadow-sm p-4 mt-4"};const i={components:{FormCheckbox:a(8369).Z},data:function(){return{criterias:{}}},created:function(){document.title="Transfer Criteria Form"},mounted:function(){this.$nextTick((function(){var e=document.getElementById("page-loading-indicator");e&&e.remove()}))},render:function(e,t,a,i,d,c){var s=(0,l.resolveComponent)("form-checkbox");return(0,l.openBlock)(),(0,l.createBlock)("div",o,[n,(0,l.createVNode)("div",r,[(0,l.createVNode)(s,{class:"mt-2",label:"1. อายุ > 18 ปี สามารถช่วยเหลือตัวเองได้ และไม่มีความเสี่ยงทางจิตเวช",modelValue:d.criterias.mature,"onUpdate:modelValue":t[1]||(t[1]=function(e){return d.criterias.mature=e})},null,8,["modelValue"]),(0,l.createVNode)(s,{class:"mt-2",label:"2. รักษาตัวในโรงพยาบาล",modelValue:d.criterias.admit,"onUpdate:modelValue":t[2]||(t[2]=function(e){return d.criterias.admit=e})},null,8,["modelValue"]),(0,l.createVNode)(s,{class:"mt-2",label:"3. วินิจฉัยเป็น",modelValue:d.criterias.diag,"onUpdate:modelValue":t[3]||(t[3]=function(e){return d.criterias.diag=e})},null,8,["modelValue"]),(0,l.createVNode)(s,{class:"mt-2",label:"3. asymptomatic  COVID-19 infection  ",modelValue:d.criterias.diag,"onUpdate:modelValue":t[4]||(t[4]=function(e){return d.criterias.diag=e})},null,8,["modelValue"]),(0,l.createVNode)(s,{class:"mt-2",label:"3. วินิจฉัยเป็น",modelValue:d.criterias.diag,"onUpdate:modelValue":t[5]||(t[5]=function(e){return d.criterias.diag=e})},null,8,["modelValue"])])])}},d=i}}]);