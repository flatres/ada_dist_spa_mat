(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[79],{9005:function(t,e,a){"use strict";a.r(e);var n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{staticClass:"q-ml-xl q-pl-lg no-scroll full-height full-width toolbar-page fixed q-pr-xl"},[a("div",{staticClass:"row flex items-center"},[a("students",{staticClass:"on-left",on:{change:t.change}}),a("q-select",{staticStyle:{"min-width":"300px"},attrs:{filled:"",dense:"",label:"Parent / Guardian",options:t.contacts},scopedSlots:t._u([{key:"no-option",fn:function(){return[a("q-item",[a("q-item-section",{staticClass:"text-font"},[t._v("\n            No Contacts\n          ")])],1)]},proxy:!0}]),model:{value:t.contact,callback:function(e){t.contact=e},expression:"contact"}})],1),a("div",{staticClass:"row fullwidth"},[t.student?a("q-badge",{staticClass:"q-ml-md q-mb-md",attrs:{outline:"",label:t.source,color:"grey"}}):t._e()],1),t.student&&t.contact?a("iframe",{staticClass:"bg-white q-ml-md q-mr-md",staticStyle:{height:"70vh",width:"1000px"},attrs:{src:t.source}}):t._e()])},s=[],o=a("0082"),l={name:"PageTransportTaxiPortal",data(){return{student:null,contact:null,contactOptions:[]}},components:{Students:o["a"]},computed:{source(){return this.student?"https://ada.marlboroughcollege.org/portal/transport?id="+this.student.misFamilyId+"&user="+this.userCode:""},userCode(){return console.log(this.contact),this.contact?this.contact.value:null}},methods:{change(t){this.contact=null;var e=t.contacts.filter((function(t){return t.portalUserInfo})).map((function(t){return{label:t.title+" "+t.firstName+" "+t.lastName,value:t.portalUserInfo.userCode}}));this.contacts=e,0!==e.length&&(this.student=t)}},created(){},mounted(){}},c=l,r=a("2be6"),i=a("8c42"),u=a("3946"),d=a("ac9b"),m=a("66dc"),h=a("f987"),p=a("e279"),f=a.n(p),g=Object(r["a"])(c,n,s,!1,null,null,null);e["default"]=g.exports;f()(g,"components",{QPage:i["a"],QSelect:u["a"],QItem:d["a"],QItemSection:m["a"],QBadge:h["a"]})}}]);