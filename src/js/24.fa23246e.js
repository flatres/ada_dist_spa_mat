(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[24],{"35ac":function(e,t,a){"use strict";var s=a("4cf1"),r=a.n(s);r.a},"4cf1":function(e,t,a){},"8cbd":function(e,t,a){"use strict";a.r(t);var s=function(){var e=this,t=e.$createElement,a=e._self._c||t;return e.getGlobalSubject?a("div",[a("year-bar",{staticClass:"yb-border"}),a("exam-bar"),a("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),a("toolbar-page",{staticClass:"t-bar",attrs:{elements:e.elements,default:"metrics"}})],1):e._e()},r=[],i=(a("e125"),a("4823"),a("2e73"),a("dde3"),a("76d0"),a("0c1f"),a("8e9e")),n=a.n(i),l=a("9ce4"),o=a("aba1"),c=a("89cf"),d=a("08e9"),u=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("loading",{attrs:{loading:e.loading,channel:"hod.metrics.metrics"}}),e.loading?e._e():a("div",{staticClass:"q-pa-md"},[a("crud",{ref:"crud",attrs:{dataOverride:e.studentData,columns:e.columns,search:"",sortBy:e.getSortBy}},[a("div",{attrs:{slot:"top-bar-left"},slot:"top-bar-left"},[a("q-btn",{staticClass:"q-mr-sm",attrs:{outline:"",color:"positive",icon:"fad fa-download"},on:{click:e.getSpreadSheet}})],1)])],1),a("q-dialog",{attrs:{persistent:"",maximized:e.maximizedToggle,"transition-show":"slide-up","transition-hide":"slide-down"},model:{value:e.controlsOpen,callback:function(t){e.controlsOpen=t},expression:"controlsOpen"}},[a("q-card",{staticClass:"bg-primary text-white"},[a("q-bar",[a("div",{staticClass:"text-h6"},[e._v("Weightings And Boundaries")]),a("q-space"),a("q-btn",{attrs:{dense:"",flat:"",icon:"minimize",disable:!e.maximizedToggle},on:{click:function(t){e.maximizedToggle=!1}}},[e.maximizedToggle?a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[e._v("Minimize")]):e._e()],1),a("q-btn",{attrs:{dense:"",flat:"",icon:"crop_square",disable:e.maximizedToggle},on:{click:function(t){e.maximizedToggle=!0}}},[e.maximizedToggle?e._e():a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[e._v("Maximize")])],1),a("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{dense:"",flat:"",icon:"close"}},[a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[e._v("Close")])],1)],1),a("q-card-section"),a("q-card-section",{staticClass:"q-pt-none"},[e._v("\n        Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum repellendus sit voluptate voluptas eveniet porro. Rerum blanditiis perferendis totam, ea at omnis vel numquam exercitationem aut, natus minima, porro labore.\n      ")])],1)],1)],1)},p=[],m=(a("288e"),a("7ba1")),f=a("d612"),h=a("10ac"),g=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-left"},[e._v(e._s(e.title))]),e._l(e.ordered,(function(t){return a("th",{key:t.grade},[e._v("\n        "+e._s("GCSE Avg"!==t.grade?t.grade:"")+"\n      ")])}))],2)]),a("tbody",[a("tr",[a("td",{staticClass:"text-left"},[e._v("All")]),e._l(e.ordered,(function(t){return a("td",{key:t.grade,staticClass:"text-center"},[e._v("\n        "+e._s("GCSE Avg"!==t.grade?t.count:"")+"\n      ")])}))],2),a("tr",[a("td",{staticClass:"text-left"},[e._v("Boys")]),e._l(e.ordered,(function(t){return a("td",{key:t.grade,staticClass:"text-center"},[e._v("\n        "+e._s(t.countM)+"\n      ")])}))],2),a("tr",[a("td",{staticClass:"text-left"},[e._v("Girls")]),e._l(e.ordered,(function(t){return a("td",{key:t.grade,staticClass:"text-center"},[e._v("\n        "+e._s(t.countF)+"\n      ")])}))],2)])])},b=[],v=(a("4fb0"),{name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{}},computed:{ordered:function(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},watch:{},components:{},created:function(){}}),y=v,k=a("2be6"),M=a("e279"),x=a.n(M),O=a("2e0b"),C=Object(k["a"])(y,g,b,!1,null,"66e4b000",null),S=C.exports;x()(C,"components",{QMarkupTable:O["a"]});var w=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{ref:"pie"})])},_=[],D=a("915b"),G=a("3099"),j=a("d610");D["f"](j["a"]);var L={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{pie:null}},methods:{createPie:function(){var e=this.profileData;this.pie=D["c"](this.$refs.pie,G["h"]);var t=this.pie,a=t.series.push(new G["i"]);a.dataFields.value="count",a.dataFields.category="grade",t.innerRadius=D["e"](30),a.slices.template.stroke=D["b"]("#fff"),a.slices.template.strokeWidth=2,a.slices.template.strokeOpacity=1,a.slices.template.cursorOverStyle=[{property:"cursor",value:"pointer"}],a.alignLabels=!0,a.labels.template.bent=!0,a.labels.template.radius=3,a.labels.template.padding(0,0,0,0),a.ticks.template.disabled=!0;var s=a.slices.template.filters.push(new D["a"]);s.opacity=0;var r=a.slices.template.states.getKey("hover"),i=r.filters.push(new D["a"]);i.opacity=.7,i.blur=5,t.data=e}},computed:{profileData:function(){var e=[];return this.profile.forEach((function(t){"GCSE Avg"!==t.grade&&e.push(t)})),e}},watch:{},components:{},created:function(){},mounted:function(){this.createPie()},beforeDestroy:function(){this.pie.dispose()}},q=L,P=Object(k["a"])(q,w,_,!1,null,"6ee2fe53",null),E=P.exports,$=function(){var e=this,t=e.$createElement,a=e._self._c||t;return a("div",[a("div",{ref:"stacked",staticStyle:{height:"200px"}})])},R=[],F=(a("c880"),a("3903"));D["f"](F["a"]);var A={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{stacked:null,chart:null}},methods:{createStacked:function(){this.stacked&&this.stacked.dispose();var e=D["c"](this.$refs.stacked,G["l"]);e.colors.list=[D["b"]("#845EC2"),D["b"]("#D65DB1"),D["b"]("#FF6F91"),D["b"]("#FF9671"),D["b"]("#FFC75F"),D["b"]("#F9F871")],this.stacked=e,e.hiddenState.properties.opacity=0,e.colors.step=4,e.padding(10,10,10,10);var t=e.xAxes.push(new G["a"]);t.dataFields.category="type",t.renderer.grid.template.location=0,t.renderer.minGridDistance=50;var a=e.yAxes.push(new G["k"]);a.min=0,a.max=100,a.strictMinMax=!0,a.calculateTotals=!0,a.renderer.minWidth=50,e.data=this.stackData(),e.legend=new G["f"]},addStackSeries:function(e,t){var a=e.series.push(new G["c"]);a.sequencedInterpolation=!0,a.columns.template.width=D["e"](80),a.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",a.name=t,a.dataFields.categoryX="type",a.dataFields.valueY=t,a.dataFields.valueYShow="totalPercent",a.dataItems.template.locations.categoryX=.5,a.stacked=!0,a.tooltip.pointerOrientation="vertical"},stackData:function(){var e=this,t=[],a=[],s={},r={},i={};return this.profile.forEach((function(e){var a=e.grade;t.push(a),s.type="All",s[a]=e.count,r.type="Boys",r[a]=e.countM,i.type="Girls",i[a]=e.countF})),a.push(s),a.push(r),a.push(i),t.forEach((function(t){e.addStackSeries(e.stacked,t)})),a}},computed:{},watch:{},components:{},created:function(){},mounted:function(){this.createStacked()},beforeDestroy:function(){this.stacked.dispose()}},N=A,T=Object(k["a"])(N,$,R,!1,null,"09a136eb",null),I=T.exports,z={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:100,loading:!0,metrics:!1,controlsOpen:!1,maximizedToggle:!1}},methods:{process:function(e){this.students=e.students,this.maxMLOCount=e.maxMLOCount,this.subject=e,this.metrics=e.metrics,this.loading=!1},getMetrics:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,m["a"].getYearMetrics(this.process,this.$errorFunction,this.year,this.exam)},openControls:function(){},getSpreadSheet:function(){var e=this;this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,m["a"].getYearMetricsSpreadsheet((function(t){e.loading=!1,e.$downloadBlob(t.url,t.file)}),(function(t){e.loading=!1}),this.year,this.exam)}},computed:{columns:function(){var e;return e=this.subject.maxMLOCount>1?[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"Min MLO",field:"mloMin",type:"string",align:"left",sortable:!0},{name:"mloMax",label:"Max MLO",field:"mloMax",type:"string",align:"left",sortable:!0}]:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!1},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"MLO",field:"mloMin",type:"string",align:"left",sortable:!0}],this.metrics?(this.metrics.GCSEMock.length>0&&(e.push({name:"gcseMockGrade",label:"GCSE Mock",field:"gcseMockGrade",type:"string",align:"left",sortable:!0}),e.push({name:"gcseMockRank",label:"Rank",field:"gcseMockRank",type:"string",align:"left",sortable:!0})),this.metrics.GCSE.length>0&&e.push({name:"gcseGrade",label:"GCSE",field:"gcseGrade",type:"string",align:"left",sortable:!0}),this.metrics.IGDR.length>0&&(e.push({name:"gcseDelta",label:"Delta",field:"gcseDelta",type:"string",align:"left",sortable:!0}),e.push({name:"gcseIGDR",label:"IGDR",field:"IGDR",type:"string",align:"left",sortable:!0})),this.metrics.ALevelMock.length>0&&(e.push({name:"aLevelMockGrade",label:"U6 Mock",field:"aLevelMockGrade",type:"string",align:"left",sortable:!0}),e.push({name:"aLevelMockPercentage",label:"%",field:"aLevelMockPercentage",type:"string",align:"left",sortable:!0}),e.push({name:"aLevelMockRank",label:"Rank",field:"aLevelMockRank",type:"string",align:"left",sortable:!0})),e):e},getSortBy:function(){return this.metrics&&this.metrics.ALevelMock.length>0?(console.log("xss"),"aLevelMockRank"):"set"},studentData:function(){var e=this,t=this.students.map((function(t){var a={id:t.id,displayName:t.displayName,classCode:t.classCode,schoolNumber:t.schoolNumber},s=t.exams.find((function(t){return t.id===e.exam}));if(s&&(a.mloMin=s.mloMin,a.mloMax=s.mloMax),!e.metrics)return a;var r=e.metrics.GCSEMock.find((function(e){return e.studentId===a.id}));r&&(a.gcseMockGrade=r.grade,a.gcseMockRank=r.cohortRank);var i=e.metrics.GCSE.find((function(e){return e.studentId===a.id}));i&&(a.gcseGrade=i.grade);var n=e.metrics.ALevelMock.find((function(e){return e.studentId===a.id}));n&&(a.aLevelMockGrade=n.grade,a.aLevelMockRank=n.cohortRank,a.aLevelMockPercentage=n.percentage);var l=e.metrics.IGDR.find((function(e){return e.studentId===a.id}));return l&&(a.gcseDelta=l.gcseDelta,a.IGDR=l.IGDR),a}));return t}},watch:{},components:{Crud:f["a"],ProfileTable:S,Loading:h["a"],ProfilePie:E,ProfileStack:I},created:function(){var e=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getMetrics(),this.subjectWatch=this.$store.watch((function(){return e.$store.getters["user/getGlobalSubject"]}),this.getMetrics),this.yearWatch=this.$store.watch((function(){return e.$store.getters["hod/activeYear"]}),this.getMetrics),this.examWatch=this.$store.watch((function(){return e.$store.getters["hod/activeExam"]}),this.getMetrics)},beforeDestroy:function(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch(),this.examWatch&&this.examWatch()}},B=z,W=(a("35ac"),a("2ef0")),Y=a("e81c"),Q=a("ebe6"),H=a("5d16"),J=a("f962c"),X=a("3aaf"),K=a("965d"),U=a("58c0"),V=Object(k["a"])(B,u,p,!1,null,"65e18029",null),Z=V.exports;function ee(e,t){var a=Object.keys(e);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(e);t&&(s=s.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),a.push.apply(a,s)}return a}function te(e){for(var t=1;t<arguments.length;t++){var a=null!=arguments[t]?arguments[t]:{};t%2?ee(Object(a),!0).forEach((function(t){n()(e,t,a[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(a)):ee(Object(a)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(a,t))}))}return e}x()(V,"components",{QBtn:W["a"],QDialog:Y["a"],QCard:Q["a"],QBar:H["a"],QSpace:J["a"],QTooltip:X["a"],QCardSection:K["a"]}),x()(V,"directives",{ClosePopup:U["a"]});var ae={name:"Page.HOD.Metrics",data:function(){return{elements:[{name:"metrics",label:"Metrics",component:Z}]}},computed:te({},Object(l["c"])("user",["getGlobalSubject"])),methods:te({},Object(l["d"])("hod",["setActiveYear"])),components:{toolbarPage:d["a"],YearBar:o["a"],ExamBar:c["a"]},created:function(){var e=this;this.setActiveYear(13),this.$store.watch((function(){return e.$store.getters["user/getGlobalSubject"]}),this.setClass),this.$store.watch((function(){return e.$store.getters["hod/activeYear"]}),this.setClass)}},se=ae,re=(a("eb8b"),Object(k["a"])(se,s,r,!1,null,null,null));t["default"]=re.exports},eb8b:function(e,t,a){"use strict";var s=a("ef76"),r=a.n(s);r.a},ef76:function(e,t,a){}}]);