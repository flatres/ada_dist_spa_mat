(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[25],{"22cc":function(t,e,s){},"366d":function(t,e,s){},"8cbd":function(t,e,s){"use strict";s.r(e);var r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.getGlobalSubject?s("div",[s("year-bar",{staticClass:"yb-border"}),s("exam-bar"),s("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),s("toolbar-page",{staticClass:"t-bar",attrs:{elements:t.elements,default:"metrics"}})],1):t._e()},a=[],n=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("8e9e")),c=s.n(n),o=s("9ce4"),i=s("aba1"),u=s("89cf"),l=s("08e9"),b=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-page",{},[s("div",{staticClass:"row fit"},[t.loaded?s("q-markup-table",{attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiaryx"},[s("th",{attrs:{colspan:"1"}},[t._v("\n            Subject\n          ")]),s("th",[t._v("Count")])])]),s("tbody",t._l(t.subjects,(function(e){return s("tr",{key:e.id,class:0===e.count?"bg-warning":""},[s("td",{staticClass:"text-left"},[t._v(t._s(e.name))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.count))])])})),0)]):t._e()],1)])},d=[],f=s("f517"),p=s("0603"),g={name:"PageResults",props:{},data:function(){return{subjects:[],loaded:!1}},methods:{process:function(t){var e=this;this.subjects=t,p["a"].getMeetingsCounts((function(t){e.subjects=t,e.loaded=!0}),null,t)}},computed:{},components:{},created:function(){f["a"].getSubjectNames(this.process)}},h=g,m=(s("bcbc"),s("2be6")),j=s("8c42"),v=s("2e0b"),O=s("e279"),y=s.n(O),w=Object(m["a"])(h,b,d,!1,null,null,null),_=w.exports;function C(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(t);e&&(r=r.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,r)}return s}function P(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?C(Object(s),!0).forEach((function(e){c()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):C(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}y()(w,"components",{QPage:j["a"],QMarkupTable:v["a"]});var x={name:"Page.HOD.Metrics",data:function(){return{elements:[{name:"meetings",label:"Meetings",component:_}]}},computed:P({},Object(o["c"])("user",["getGlobalSubject"])),methods:P({},Object(o["d"])("hod",["setActiveYear"])),components:{toolbarPage:l["a"],YearBar:i["a"],ExamBar:u["a"]},created:function(){var t=this;this.setActiveYear(13),this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.setClass),this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.setClass)}},S=x,k=(s("eb8b"),Object(m["a"])(S,r,a,!1,null,null,null));e["default"]=k.exports},bcbc:function(t,e,s){"use strict";var r=s("366d"),a=s.n(r);a.a},eb8b:function(t,e,s){"use strict";var r=s("22cc"),a=s.n(r);a.a}}]);