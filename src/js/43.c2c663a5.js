(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[43],{"1e83":function(e,t,a){"use strict";var r=a("74d9"),s=a.n(r);s.a},"74d9":function(e,t,a){},"90b2e":function(e,t,a){"use strict";a.r(t);var r=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"col column fit q-pl-xs"},[a("year-bar",{staticClass:"ybx-border q-ma-xs"}),a("toolbar-page",{staticClass:"col column",attrs:{elements:e.elements,default:"overview"}})],1)},s=[],o=a("08e9"),n=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",{staticClass:"scroll col full-width toolbar-page fixed q-pr-xl column"},[a("div",{staticClass:"col column"},[a("div",{staticClass:"q-pr-sm col row q-pt-lg q-mb-xl q-pb-xl"},[a("q-table",{staticClass:"my-sticky-header-table col column full-height",attrs:{dense:"",data:e.filteredOverview,columns:e.columns,"row-key":"id",stylex:"width:700px",flat:"","virtual-scroll":"",pagination:e.pagination,"rows-per-page-options":[0]},on:{"update:pagination":function(t){e.pagination=t}},scopedSlots:e._u([{key:"body",fn:function(t){return[a("q-tr",{staticClass:"wyap-table",attrs:{props:t}},[a("q-td",{key:"number",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.schoolNumber))]),a("q-td",{key:"name",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.displayName))]),a("q-td",{key:"gender",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.gender))]),a("q-td",{key:"house",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.boardingHouseCode))]),a("q-td",{key:"baseline",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.baseline))]),a("q-td",{key:"tagPoints",attrs:{props:t,"auto-width":""}},[e._v(e._s(t.row.tagPoints))]),a("q-td",{key:"tags",attrs:{props:t,"auto-width":""}},e._l(t.row.exams,(function(t){return a("span",{key:t.id},[e._v("\n              "+e._s(t.tag)+"\n              "),a("q-tooltip",[e._v(e._s(t.examCode)+" - "+e._s(t.examName))])],1)})),0),a("q-td")],1)]}}])}),a("div",{staticClass:"col-3 bg-red"})],1)])])},l=[],i=(a("e125"),a("4823"),a("2e73"),a("76d0"),a("8e9e")),c=a.n(i),d=a("84a8"),p=a("9ce4");function u(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var r=Object.getOwnPropertySymbols(e);t&&(r=r.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,r)}return a}function b(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?u(Object(a),!0).forEach((function(t){c()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):u(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}var v,m,g={name:"Page.Dha.Tags.Overview",data(){return{exams:[],columns:[{name:"number",align:"left",label:"Sch #",field:"schoolNumber",sortable:!0},{name:"name",required:!0,label:"Name",align:"left",field:"displayName",sortable:!0},{name:"gender",required:!0,label:"M/F",align:"left",field:"gender",sortable:!0},{name:"house",align:"left",label:"Hs",field:"boardingHouseCode",sortable:!0},{name:"baseline",align:"left",label:"Baseline",field:"baseline",sortable:!0},{name:"tagPoints",align:"left",label:"Points",field:"tagPoints",sortable:!0},{name:"tags",align:"left",label:"TAGs"}],overview:[],pagination:{rowsPerPage:0}}},computed:b(b({},Object(p["c"])("dha",["activeYear"])),{},{filteredOverview(){return this.overview}}),methods:{getOverview(){this.$startLoading(),d["a"].getOverview(this.processOverview,this.$errorHandler,this.activeYear)},processOverview(e){this.overview=e,this.$endLoading()}},components:{},created(){var e=this;this.yearWatch=this.$store.watch((function(){return e.$store.getters["dha/activeYear"]}),this.getOverview),this.getOverview()},beforeDestroy(){this.yearWatch&&this.yearWatch()}},f=g,w=(a("1e83"),a("2be6")),h=a("18f0"),y=a("41c9"),O=a("b74b"),_=a("3aaf"),q=a("dfd0"),P=a("e279"),x=a.n(P),j=Object(w["a"])(f,n,l,!1,null,null,null),k=j.exports;x()(j,"components",{QTable:h["a"],QTr:y["a"],QTd:O["a"],QTooltip:_["a"],QColor:q["a"]});var C={},D=Object(w["a"])(C,v,m,!1,null,null,null),T=D.exports,$=a("6247"),N={name:"Page.DHA.Tags",data(){return{elements:[{name:"overview",label:"Overview",component:k},{name:"pupils",label:"pupils",component:T}]}},components:{toolbarPage:o["a"],YearBar:$["a"]}},H=N,Q=Object(w["a"])(H,r,s,!1,null,null,null);t["default"]=Q.exports}}]);