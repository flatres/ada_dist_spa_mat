(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[69],{5549:function(e,t,a){"use strict";a.r(t);var c=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("module-index",{attrs:{icon:e.icon,module:e.module,routes:e.routes,selectSubject:""},on:{changeSubject:e.changeSubject}})},o=[],n=a("ca92"),u={name:"PageHodIndex",data(){return{icon:"fal fa-user-class",module:"hod",routes:[{label:"Metrics",page:"metrics",icon:"fad fa-chart-pie",route:"/hod/metrics"},{label:"Science",page:"science",icon:"fad fa-dna",route:"/hod/science"},{label:"Lab",page:"lab",icon:"fad fa-flask",route:"/hod/lab"}]}},components:{moduleIndex:n["a"]},computed:{},methods:{changeSubject(e){this.subject=e}},created(){this.$router.push("/hod/metrics")},mounted(){}},s=u,d=a("2be6"),l=Object(d["a"])(s,c,o,!1,null,null,null);t["default"]=l.exports}}]);