(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[26],{"0ed4":function(t,e,s){"use strict";var a=s("8db2"),n=s.n(a);n.a},"4f9b":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("toolbar-page",{attrs:{elements:t.elements,default:"dashboard"}})},n=[],o=s("08e9"),r=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",{staticClass:"row"},[s("div",{staticClass:"col-8 q-mr-lg"},[s("q-scroll-area",{staticStyle:{height:"80vh"}},[s("div",{staticClass:"q-pa-md"},[s("q-markup-table",{staticClass:"text-white no-shadow",attrs:{dense:""}},[s("tbody",t._l(t.logs.slice().reverse(),(function(e){return s("tr",{key:e.id,class:t.logColor(e)},[s("td",{staticClass:"text-left",staticStyle:{width:"150px"}},[t._v(t._s(e.time))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.message))])])})),0)])],1)])],1),s("div",{staticClass:"col"},[s("p",{staticClass:"text-primary text-h5"},[t._v("CPU")]),s("div",{staticClass:"full-width text-center"},[s("q-circular-progress",{staticClass:"q-ma-md text-primary",attrs:{"show-value":"","font-size":"31px",indeterminate:t.indeterminate,value:100-t.resources.cpuIdle,size:"200px",thickness:"0.22",color:"primary","track-color":"grey-8"}},[t._v("\n      "+t._s(100-t.resources.cpuIdle)+"%\n      ")])],1)])])},i=[],c=s("4778"),l={getLogs:function(t,e,s){c["a"].get("/admin/logs").then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},d={name:"Admin.Logs.Dashboard",data:function(){return{logs:[],resources:{cpuIdle:0},indeterminate:!0}},computed:{},methods:{fetchLogs:function(){l.getLogs(this.processLog)},processLog:function(t){this.logs=t.messages,this.resources=t.resources,this.indeterminate=!1},logColor:function(t){switch(t.level){case"CRITICAL":return"text-white bg-red";default:return"text-white"}}},components:{},created:function(){this.fetchLogs(),this.interval=setInterval(function(){this.fetchLogs()}.bind(this),1e3)},beforeDestroy:function(){clearInterval(this.interval)}},u=d,h=(s("0ed4"),s("2be6")),f=Object(h["a"])(u,r,i,!1,null,"3edf616a",null),m=f.exports,g={name:"Page.Admin.Logs",data:function(){return{elements:[{name:"dashboard",label:"dashboard",component:m}]}},components:{toolbarPage:o["a"]},methods:{refresh:function(){}}},p=g,v=Object(h["a"])(p,a,n,!1,null,null,null);e["default"]=v.exports},"8db2":function(t,e,s){}}]);