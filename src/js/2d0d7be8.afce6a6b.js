(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([["2d0d7be8"],{"77b4":function(t,e,a){"use strict";a.r(e);var l=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"q-ml-lg",staticStyle:{heightx:"100vh"}},[a("br"),a("div",{staticClass:"row q-gutter-xs"},[a("q-btn",{staticClass:"q-mr-sm",attrs:{outline:"",color:"primary","text-color":"primary",icon:"refresh"},on:{click:t.getTemplates}}),a("div",{staticClass:"col"},[a("q-select",{attrs:{dense:"",dark:"",filledx:"",options:t.templateList,label:"Template"},on:{input:t.changeTemplate},model:{value:t.templateModel,callback:function(e){t.templateModel=e},expression:"templateModel"}})],1),a("div",{staticClass:"col bg-dark text-white"},[a("q-input",{attrs:{dark:"",label:"To",dense:""},model:{value:t.to,callback:function(e){t.to=e},expression:"to"}})],1),a("div",{staticClass:"col bg-dark text-white"},[a("q-input",{attrs:{dark:"",label:"Subject",dense:""},model:{value:t.subject,callback:function(e){t.subject=e},expression:"subject"}})],1)],1),a("h4",[t._v("Fields")]),t._l(t.template.vars,(function(e){return a("q-input",{key:e.label,attrs:{dark:"",label:e.label,dense:""},model:{value:e.value,callback:function(a){t.$set(e,"value",a)},expression:"field.value"}})})),a("div",{staticClass:"q-pa-md bg-dark text-black"},[a("q-btn",{staticClass:"full-width q-mt-md",attrs:{color:"primary",outline:"","text-color":"primary",label:"Send"},on:{click:t.send}})],1),a("div",{staticClass:"q-pa-md bg-grey-10 text-black"},[a("q-editor",{attrs:{"min-height":"5rem",flat:"","content-class":"bg-white","toolbar-text-color":"grey-3","toolbar-toggle-color":"yellow-8","toolbar-flat":"","toolbar-bg":"grey-9"},model:{value:t.body,callback:function(e){t.body=e},expression:"body"}})],1)],2)},s=[],o=(a("288e"),a("c880"),a("4778")),n={postEmail:function(t,e,a){o["a"].post("/lab/email",a).then((function(t){})).catch((function(t){console.log(t),e&&e()}))},getTemplates:function(t,e,a){o["a"].get("/lab/email/templates").then((function(e){t(e.data)})).catch((function(t){console.log(t),e&&e()}))}},i={name:"PageLabEmail",data:function(){return{to:"flatres@gmail.com",subject:"I am a subject",body:"I am a body",templates:[],templateList:[],template:[],templateModel:null,name:null}},methods:{send:function(){var t={to:this.to,subject:this.subject,body:this.body,template:this.templateModel,fields:this.template.vars};n.postEmail(this.success,null,t)},success:function(){},process:function(t){this.templates=t,this.templateList=t.map((function(t){return t.filename})),this.name&&this.changeTemplate(this.name)},changeTemplate:function(t){this.name=t,this.template=this.templates.find((function(e){return e.filename===t})),this.body=this.template.data},getTemplates:function(){n.getTemplates(this.process)}},components:{},created:function(){this.getTemplates()}},c=i,r=a("2be6"),m=Object(r["a"])(c,l,s,!1,null,null,null);e["default"]=m.exports}}]);