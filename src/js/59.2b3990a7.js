(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[59],{"2d78":function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("toolbar-page",{attrs:{elements:t.elements,default:"alis"}})},o=[],r=a("08e9"),n=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{staticClass:"no-scroll full-height full-width toolbar-page fixed q-pr-xl q-pa-md column"},[a("div",{staticClass:"row",staticStyle:{height:"30px"}},[a("year-bar",{staticClass:"ybx-border q-ma-xs",attrs:{upper:""}}),a("q-btn",{staticClass:"q-ml-none",attrs:{icon:"fad fa-download fa-xs",size:"sm",dense:"",color:"positive",flat:"",unelevated:"",loading:t.downloadLoading},on:{click:t.downloadData}})],1),a("q-scroll-area",{staticClass:"q-pr-sm col column q-pt-lg q-mb-xl q-pb-xl scroll",staticStyle:{"overflow-x":"scroll"},attrs:{horizontal:""}},[a("q-table",{staticClass:"sticky-header-table sticky-column-table col column full-height",attrs:{dense:"",data:t.students,columns:t.columns,"row-key":"id",stylex:"width:700px",flat:"","virtual-scroll":"",pagination:t.pagination,"rows-per-page-options":[0]},on:{"update:pagination":function(e){t.pagination=e}},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{staticClass:"wyap-table",attrs:{props:e}},[a("q-td",{key:"number",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.schoolNumber))]),a("q-td",{key:"name",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.displayName))]),a("q-td",{key:"gender",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.gender))]),a("q-td",{key:"house",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.boardingHouseCode))]),t._l(t.exams,(function(s){return a("q-td",{key:s,staticStyle:{width:"10px"},attrs:{props:e,"auto-width":""}},[t._v("\n              "+t._s(e.row[s])+"\n            ")])})),a("q-td")],2)]}}])})],1)],1)},l=[],i=(a("e125"),a("4823"),a("2e73"),a("76d0"),a("7f3a")),d=a.n(i),c=a("8e9e"),u=a.n(c),p=(a("632c"),a("47783")),h={getAlis(t,e,a){p["a"].get("/dha/baseline/alis/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getMidyis(t,e,a){p["a"].get("/dha/baseline/midyis/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},b=a("9ce4"),m=a("6247");function g(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function f(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?g(Object(a),!0).forEach((function(e){u()(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):g(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var w={name:"PageDha.Alis  ",data(){return{students:[],exams:[],totals:[],pointsAvg:0,downloadLoading:!1,yearWatch:null}},methods:{getAlis(){this.$startLoading(),h.getAlis(this.processAlis,this.$errorHandler,this.activeYear)},processAlis(t){this.$endLoading(),this.students=t.students.sort(this.$compare("lastName","asc")),this.exams=t.exams},downloadData(){var t=this;if(this.$startLoading(),!0!==this.downloadLoading){this.downloadLoading=!0;var e=this.columns,a={columns:e,data:this.students};p["a"].post("/tools/crud/sheet",a).then((function(e){t.$endLoading(),t.$downloadBlob(e.data.url,e.data.file),t.downloadLoading=!1})).catch((function(e){t.downloadLoading=!1,t.errorHandler(e)}))}}},computed:f(f({},Object(b["c"])("dha",["activeYear"])),{},{columns(){var t=[{name:"number",align:"left",label:"Sch #",field:"schoolNumber",sortable:!0},{name:"name",required:!0,label:"Name",align:"left",field:"displayName",sortable:!0},{name:"gender",required:!0,label:"M/F",align:"left",field:"gender",sortable:!0},{name:"house",align:"left",label:"Hs",field:"boardingHouseCode",sortable:!0}],e=this.exams.map((function(t){return{name:t,label:t,field:t,sortable:!1}}));return[].concat(t,d()(e))}}),components:{YearBar:m["a"]},created(){var t=this;this.yearWatch=this.$store.watch((function(){return t.$store.getters["dha/activeYear"]}),this.getAlis),this.getAlis()},beforeDestroy(){this.yearWatch&&this.yearWatch()}},y=w,v=a("2be6"),q=a("8c42"),O=a("2ef0"),x=a("26a8"),_=a("18f0"),j=a("41c9"),k=a("b74b"),P=a("e279"),A=a.n(P),$=Object(v["a"])(y,n,l,!1,null,null,null),L=$.exports;A()($,"components",{QPage:q["a"],QBtn:O["a"],QScrollArea:x["a"],QTable:_["a"],QTr:j["a"],QTd:k["a"]});var C=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-page",{staticClass:"no-scroll full-height full-width toolbar-page fixed q-pr-xl q-pa-md column"},[a("div",{staticClass:"row",staticStyle:{height:"30px"}},[a("year-bar",{staticClass:"ybx-border q-ma-xs",attrs:{lower:""}}),a("q-btn",{staticClass:"q-ml-none",attrs:{icon:"fad fa-download fa-xs",size:"sm",dense:"",color:"positive",flat:"",unelevated:"",loading:t.downloadLoading},on:{click:t.downloadData}})],1),a("q-scroll-area",{staticClass:"q-pr-sm col column q-pt-lg q-mb-xl q-pb-xl scroll",staticStyle:{"overflow-x":"scroll"},attrs:{horizontal:""}},[a("q-table",{staticClass:"sticky-header-table sticky-column-table col column full-height",attrs:{dense:"",data:t.students,columns:t.columns,"row-key":"id",stylex:"width:700px",flat:"","virtual-scroll":"",pagination:t.pagination,"rows-per-page-options":[0]},on:{"update:pagination":function(e){t.pagination=e}},scopedSlots:t._u([{key:"body",fn:function(e){return[a("q-tr",{staticClass:"wyap-table",attrs:{props:e}},[a("q-td",{key:"number",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.schoolNumber))]),a("q-td",{key:"name",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.displayName))]),a("q-td",{key:"gender",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.gender))]),a("q-td",{key:"house",attrs:{props:e,"auto-width":""}},[t._v(t._s(e.row.boardingHouseCode))]),t._l(t.exams,(function(s){return a("q-td",{key:s,staticStyle:{width:"10px"},attrs:{props:e,"auto-width":""}},[t._v("\n              "+t._s(e.row[s])+"\n            ")])})),a("q-td")],2)]}}])})],1)],1)},D=[];function S(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function N(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?S(Object(a),!0).forEach((function(e){u()(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):S(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}var Q={name:"PageDha.Alis  ",data(){return{students:[],exams:[],totals:[],pointsAvg:0,downloadLoading:!1,yearWatch:null}},methods:{getAlis(){this.$startLoading(),h.getMidyis(this.processAlis,this.$errorHandler,this.activeYear)},processAlis(t){this.$endLoading(),this.students=t.students.sort(this.$compare("lastName","asc")),this.exams=t.exams},downloadData(){var t=this;if(this.$startLoading(),!0!==this.downloadLoading){this.downloadLoading=!0;var e=this.columns,a={columns:e,data:this.students};p["a"].post("/tools/crud/sheet",a).then((function(e){t.$endLoading(),t.$downloadBlob(e.data.url,e.data.file),t.downloadLoading=!1})).catch((function(e){t.downloadLoading=!1,t.errorHandler(e)}))}}},computed:N(N({},Object(b["c"])("dha",["activeYear"])),{},{columns(){var t=[{name:"number",align:"left",label:"Sch #",field:"schoolNumber",sortable:!0},{name:"name",required:!0,label:"Name",align:"left",field:"displayName",sortable:!0},{name:"gender",required:!0,label:"M/F",align:"left",field:"gender",sortable:!0},{name:"house",align:"left",label:"Hs",field:"boardingHouseCode",sortable:!0}],e=this.exams.map((function(t){return{name:t,label:t,field:t,sortable:!1}}));return[].concat(t,d()(e))}}),components:{YearBar:m["a"]},created(){var t=this;this.yearWatch=this.$store.watch((function(){return t.$store.getters["dha/activeYear"]}),this.getAlis),this.getAlis()},beforeDestroy(){this.yearWatch&&this.yearWatch()}},H=Q,W=Object(v["a"])(H,C,D,!1,null,null,null),Y=W.exports;A()(W,"components",{QPage:q["a"],QBtn:O["a"],QScrollArea:x["a"],QTable:_["a"],QTr:j["a"],QTd:k["a"]});var B={name:"Page.DHA.Baseline",data(){return{elements:[{name:"alis",label:"Alis",component:L},{name:"midyis",label:"Midyis",component:Y}]}},components:{Alis:L,toolbarPage:r["a"]}},E=B,T=Object(v["a"])(E,s,o,!1,null,null,null);e["default"]=T.exports}}]);