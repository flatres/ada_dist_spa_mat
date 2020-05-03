(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[13],{"091a":function(t,e,s){},5167:function(t,e,s){"use strict";var a=s("d501"),r=s.n(a);r.a},"762e":function(t,e,s){"use strict";var a=s("091a"),r=s.n(a);r.a},"773c":function(t,e,s){},"8cbd":function(t,e,s){"use strict";s.r(e);var a=function(){var t=this,e=t.$createElement,s=t._self._c||e;return t.getGlobalSubject?s("div",[s("year-bar",{staticClass:"yb-border"}),s("exam-bar"),s("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),s("toolbar-page",{staticClass:"t-bar",attrs:{elements:t.elements,default:"classes"}})],1):t._e()},r=[],i=(s("e125"),s("4823"),s("2e73"),s("dde3"),s("76d0"),s("0c1f"),s("8e9e")),n=s.n(i),l=s("9ce4"),o=s("aba1"),c=s("89cf"),d=s("08e9"),u=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("loading",{attrs:{loading:t.loadingClasses}}),!t.loadingClasses&&t.show&&t.classes.length>0?s("div",{staticClass:"row no-wrap q-px-xs q-pt-xs text-left",staticStyle:{"padding-bottom":"200px"}},[s("div",{staticClass:"q-mr-md",staticStyle:{"min-width":"150px"}},[s("q-scroll-area",{staticStyle:{height:"80vh"}},[s("q-list",{staticClass:"no-padding"},t._l(t.classes,(function(e){return s("q-item",{key:e.id,staticClass:"q-pa-xs text-white bg-tertiary q-my-xs",class:e.id===t.selectedClassId?"active-class":"",staticStyle:{border:"1px solid grey"},attrs:{clickable:"",group:"classes",dense:""},on:{click:function(s){return t.setClass(e.id)}}},[s("q-item-section",{attrs:{top:""}},[s("q-item-label",{staticClass:"row"},[s("div",{staticClass:"col text-h7 text-boldx text-font q-mt-xs text-center"},[s("span",{staticClass:"q-mr-md"},[t._v(t._s(e.code))])])])],1)],1)})),1)],1)],1),s("div",{staticClass:"col"},[s("loading",{attrs:{loading:t.loading}}),t.loading?t._e():s("q-markup-table",{attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiaryx"},[s("th",{attrs:{colspan:"3"}}),t._l(t.teachers,(function(e){return s("th",{key:e.id,staticClass:"bg-tertiary text-center",attrs:{colspan:t.exams.length}},[t._v("\n              MLO - "+t._s(e.login)+"\n            ")])}))],2),s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-left"},[t._v("Name")]),s("th",{staticClass:"text-left"},[t._v("Class")]),s("th",{staticClass:"text-left"},[t._v("Boarding")]),t._l(t.teachers,(function(e){return s("th",{key:e.id,attrs:{colspan:t.exams.length}},[s("table",{staticClass:"full-width no-border"},t._l(t.exams,(function(e){return s("th",{key:e.id,staticClass:"text-center no-border"},[t._v(t._s(e.examCode)+"\n                ")])})),0)])}))],2)]),s("tbody",t._l(t.students,(function(e){return s("tr",{key:e.id},[s("td",{staticClass:"text-left"},[t._v(t._s(e.fullName))]),s("td",{staticClass:"text-left"},[t._v(t._s(t.classCode))]),s("td",{staticClass:"text-left"},[t._v(t._s(e.boardingHouse))]),t._l(t.teachers,(function(a){return s("td",{key:a.id,attrs:{colspan:t.exams.length}},[s("table",{staticClass:"full-width no-border"},t._l(t.exams,(function(r){return s("td",{key:r.id,staticClass:"text-center no-border"},[t._v(t._s(t.parseMLO(e,r,a)))])})),0)])}))],2)})),0)])],1)]):t._e()],1)},h=[],f=(s("288e"),s("7ba1")),p=s("268c"),m=s("10ac"),g={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,classes:[],selectedClassId:null,show:!1,exams:[],teachers:[],classCode:[],loading:!1,loadingClasses:!0}},methods:{processMetrics:function(t){this.exams=t.exams,this.teachers=t.teachers,this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.classCode=t.code,this.loading=!1},processClasses:function(t){this.classes=t,this.loadingClasses=!1,this.classes.length>0&&(this.show=!0,this.selectedClassId=this.classes[0].id,this.getClassMetrics())},getExamClasses:function(){this.loadingClasses=!0,p["a"].getExamClasses(this.processClasses,this.$errorFunction)},getMLO:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],f["a"].getYearMLO(this.process,this.$errorHandler,this.year,this.exam)},setClass:function(t){this.selectedClassId=t,this.getClassMetrics()},getClassMetrics:function(){this.loading=!0,this.selectedClassId&&f["a"].getClassMetrics(this.processMetrics,this.$errorHandler,this.selectedClassId)},parseMLO:function(t,e,s){var a=t.examData.mlo.find((function(t){return t.teacher.id===s.id&&t.examId===e.id}));return a.mlo?a.mlo:"."}},computed:{columns:function(){for(var t=[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"boarding",label:"Boarding",field:"boardingHouse",type:"string",align:"left",sortable:!0},{name:"mlo0",label:"MLO",field:"mlo0",type:"number",align:"left",sortable:!0}],e=1;e<this.maxMLOCount;e++){var s=e+1;t.push({name:"mlo"+e,label:"MLO "+s,field:"mlo"+e,type:"number",align:"left",sortable:!0,hidden:!1})}return t}},components:{Loading:m["a"]},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getExamClasses(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getExamClasses),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getExamClasses),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getExamClasses)},beforeDestroy:function(){this.subjectWatch(),this.yearWatch(),this.examWatch()}},b=g,y=(s("df3b"),s("2be6")),v=s("e279"),x=s.n(v),C=s("26a8"),O=s("6c93"),M=s("ac9b"),_=s("66dc"),k=s("7d9a"),w=s("2e0b"),j=Object(y["a"])(b,u,h,!1,null,"1313da97",null),S=j.exports;x()(j,"components",{QScrollArea:C["a"],QList:O["a"],QItem:M["a"],QItemSection:_["a"],QItemLabel:k["a"],QMarkupTable:w["a"]});var $=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("loading",{attrs:{loading:t.loading}}),t.loading?t._e():s("q-splitter",{staticStyle:{height:"90vh"},scopedSlots:t._u([{key:"before",fn:function(){return[s("div",{staticClass:"q-pa-md"},[s("crud",{ref:"crud",attrs:{dataOverride:t.studentData,columns:t.columns,search:"",sortBy:"set",download:""}})],1)]},proxy:!0},{key:"after",fn:function(){return[s("div",{staticClass:"q-pa-md"},[s("profile-table",{attrs:{profile:t.subject.mloMaxGradeProfile,title:t.subject.maxMLOCount>1?"Max":""}}),s("profile-pie",{attrs:{profile:t.subject.mloMaxGradeProfile}}),t.subject.maxMLOCount>1?s("profile-table",{attrs:{profile:t.subject.mloMinGradeProfile,title:"Min"}}):t._e(),t.subject.maxMLOCount>1?s("profile-pie",{attrs:{profile:t.subject.mloMinGradeProfile,title:"Max"}}):t._e()],1)]},proxy:!0}],null,!1,2342938711),model:{value:t.splitter,callback:function(e){t.splitter=e},expression:"splitter"}})],1)},L=[],q=s("d612"),E=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-left"},[t._v(t._s(t.title))]),t._l(t.ordered,(function(e){return s("th",{key:e.grade},[t._v("\n        "+t._s("GCSE Avg"!==e.grade?e.grade:"")+"\n      ")])}))],2)]),s("tbody",[s("tr",[s("td",{staticClass:"text-left"},[t._v("All")]),t._l(t.ordered,(function(e){return s("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s("GCSE Avg"!==e.grade?e.count:"")+"\n      ")])}))],2),s("tr",[s("td",{staticClass:"text-left"},[t._v("Boys")]),t._l(t.ordered,(function(e){return s("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s(e.countM)+"\n      ")])}))],2),s("tr",[s("td",{staticClass:"text-left"},[t._v("Girls")]),t._l(t.ordered,(function(e){return s("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s(e.countF)+"\n      ")])}))],2)])])},D=[],P=(s("4fb0"),{name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{}},computed:{ordered:function(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},watch:{},components:{},created:function(){}}),F=P,A=Object(y["a"])(F,E,D,!1,null,"1b591d1a",null),G=A.exports;x()(A,"components",{QMarkupTable:w["a"]});var H=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{ref:"pie"})])},Y=[],N=s("915b"),I=s("3099"),T=s("d610");N["f"](T["a"]);var Q={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{pie:null}},methods:{createPie:function(){var t=this.profile;this.pie=N["c"](this.$refs.pie,I["h"]);var e=this.pie,s=e.series.push(new I["i"]);s.dataFields.value="count",s.dataFields.category="grade",e.innerRadius=N["e"](30),s.slices.template.stroke=N["b"]("#fff"),s.slices.template.strokeWidth=2,s.slices.template.strokeOpacity=1,s.slices.template.cursorOverStyle=[{property:"cursor",value:"pointer"}],s.alignLabels=!0,s.labels.template.bent=!0,s.labels.template.radius=3,s.labels.template.padding(0,0,0,0),s.ticks.template.disabled=!0;var a=s.slices.template.filters.push(new N["a"]);a.opacity=0;var r=s.slices.template.states.getKey("hover"),i=r.filters.push(new N["a"]);i.opacity=.7,i.blur=5,e.data=t}},watch:{},components:{},created:function(){},mounted:function(){this.createPie()},beforeDestroy:function(){this.pie.dispose()}},B=Q,W=Object(y["a"])(B,H,Y,!1,null,"7ccab459",null),X=W.exports,z=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{ref:"stacked",staticStyle:{height:"200px"}})])},J=[],K=(s("c880"),s("3903"));N["f"](K["a"]);var R={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{stacked:null,chart:null}},methods:{createStacked:function(){this.stacked&&this.stacked.dispose();var t=N["c"](this.$refs.stacked,I["l"]);t.colors.list=[N["b"]("#845EC2"),N["b"]("#D65DB1"),N["b"]("#FF6F91"),N["b"]("#FF9671"),N["b"]("#FFC75F"),N["b"]("#F9F871")],this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new I["a"]);e.dataFields.category="type",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var s=t.yAxes.push(new I["k"]);s.min=0,s.max=100,s.strictMinMax=!0,s.calculateTotals=!0,s.renderer.minWidth=50,t.data=this.stackData(),t.legend=new I["f"]},addStackSeries:function(t,e){var s=t.series.push(new I["c"]);s.sequencedInterpolation=!0,s.columns.template.width=N["e"](80),s.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",s.name=e,s.dataFields.categoryX="type",s.dataFields.valueY=e,s.dataFields.valueYShow="totalPercent",s.dataItems.template.locations.categoryX=.5,s.stacked=!0,s.tooltip.pointerOrientation="vertical"},stackData:function(){var t=this,e=[],s=[],a={},r={},i={};return this.profile.forEach((function(t){var s=t.grade;e.push(s),a.type="All",a[s]=t.count,r.type="Boys",r[s]=t.countM,i.type="Girls",i[s]=t.countF})),s.push(a),s.push(r),s.push(i),e.forEach((function(e){t.addStackSeries(t.stacked,e)})),s}},computed:{},watch:{},components:{},created:function(){},mounted:function(){this.createStacked()},beforeDestroy:function(){this.stacked.dispose()}},U=R,V=Object(y["a"])(U,z,J,!1,null,"d3552750",null),Z=V.exports,tt={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0}},methods:{process:function(t){this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1},getMLO:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,f["a"].getYearMLO(this.process,this.$errorFunction,this.year,this.exam)}},computed:{columns:function(){return this.subject.maxMLOCount>1?[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"Min MLO",field:"mloMin",type:"string",align:"left",sortable:!0},{name:"mloMax",label:"Max MLO",field:"mloMax",type:"string",align:"left",sortable:!0}]:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"MLO",field:"mloMin",type:"string",align:"left",sortable:!0}]},studentData:function(){var t=this,e=this.students.map((function(e){var s={id:e.id,displayName:e.displayName,classCode:e.classCode},a=e.exams.find((function(e){return e.id===t.exam}));return a&&(s.mloMin=a.mloMin,s.mloMax=a.mloMax),s}));return e}},watch:{},components:{Crud:q["a"],ProfileTable:G,Loading:m["a"],ProfilePie:X,ProfileStack:Z},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getMLO(),this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getMLO),this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getMLO),this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getMLO)}},et=tt,st=(s("762e"),s("bc4f")),at=Object(y["a"])(et,$,L,!1,null,"0a8c95da",null),rt=at.exports;x()(at,"components",{QSplitter:st["a"]});var it=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("loading",{attrs:{loading:t.loading}}),t.loading?t._e():s("history-table",{attrs:{history:t.subject.history,grades:t.subject.grades}}),t.loading?t._e():s("history-stack",{attrs:{profile:t.subject.stackedHistory,grades:t.subject.grades}})],1)},nt=[],lt=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("q-btn-toggle",{attrs:{spread:"","no-caps":"",dense:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.modes,year:"full-width"},on:{input:t.change},model:{value:t.mode,callback:function(e){t.mode=e},expression:"mode"}}),s("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[s("thead",{staticClass:"q-mb-md"},[s("tr",{staticClass:"bg-tertiary"},[s("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.grades,(function(e){return s("th",{key:e.grade},[t._v("\n          "+t._s(e.grade)+"\n        ")])}))],2)]),s("tbody",t._l(t.history,(function(e){return s("tr",{key:e.year},[s("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.grades,(function(a){return s("td",{key:a.grade,staticClass:"text-center"},[t._v("\n          "+t._s(t.getGrade(a.grade,e))+"\n        ")])}))],2)})),0)])],1)},ot=[],ct={name:"HOD.Metrics.MLO.ProfileTable",props:{history:{type:Array,required:!0},grades:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{mode:"percentage",modes:[{value:"percentage",label:"Percentage"},{value:"absolute",label:"Absolute"}]}},computed:{ordered:function(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},methods:{getGrade:function(t,e){var s=e.results.find((function(e){return e.grade===t}));if(!s)return"-";if("absolute"===this.mode||"GCSE Avg"===t)return s.count;var a=0;return e.results.forEach((function(t){a+=t.count})),0===a?"NAN":Math.round(100*s.count/a,1)}},watch:{},components:{},created:function(){}},dt=ct,ut=s("6dd6"),ht=Object(y["a"])(dt,lt,ot,!1,null,"147f2d9e",null),ft=ht.exports;x()(ht,"components",{QBtnToggle:ut["a"],QMarkupTable:w["a"]});var pt=function(){var t=this,e=t.$createElement,s=t._self._c||e;return s("div",[s("div",{ref:"stacked",staticStyle:{height:"200px"}})])},mt=[];N["f"](K["a"]);var gt={name:"HOD.Metrics.MLO.HistoryStack",props:{profile:{type:Array,required:!0},grades:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{stacked:null,chart:null}},methods:{createStacked:function(){this.stacked&&this.stacked.dispose();var t=N["c"](this.$refs.stacked,I["l"]);this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new I["a"]);e.dataFields.category="year",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var s=t.yAxes.push(new I["k"]);s.min=0,s.max=100,s.strictMinMax=!0,s.calculateTotals=!0,s.renderer.minWidth=50,t.data=this.stackData(),t.legend=new I["f"]},addStackSeries:function(t,e){var s=t.series.push(new I["c"]);s.sequencedInterpolation=!0,s.columns.template.width=N["e"](80),s.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",s.name=e,s.dataFields.categoryX="year",s.dataFields.valueY=e,s.dataFields.valueYShow="totalPercent",s.dataItems.template.locations.categoryX=.5,s.stacked=!0,s.tooltip.pointerOrientation="vertical"},stackData:function(){var t=this;return this.grades.forEach((function(e){"GCSE Avg"!==e.grade&&t.addStackSeries(t.stacked,e.grade)})),console.log(this.profile),this.profile}},computed:{},watch:{},components:{},created:function(){},mounted:function(){this.createStacked()},beforeDestroy:function(){this.stacked.dispose()}},bt=gt,yt=Object(y["a"])(bt,pt,mt,!1,null,"d21e0bf4",null),vt=yt.exports,xt={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0}},methods:{process:function(t){this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1},getHistory:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,f["a"].getExamHistory(this.process,this.$errorFunction,this.year,this.exam)}},computed:{},watch:{},components:{HistoryTable:ft,HistoryStack:vt,Loading:m["a"]},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getHistory(),this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getHistory),this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getHistory),this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getHistory)}},Ct=xt,Ot=(s("5167"),Object(y["a"])(Ct,it,nt,!1,null,"140637fc",null)),Mt=Ot.exports;function _t(t,e){var s=Object.keys(t);if(Object.getOwnPropertySymbols){var a=Object.getOwnPropertySymbols(t);e&&(a=a.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),s.push.apply(s,a)}return s}function kt(t){for(var e=1;e<arguments.length;e++){var s=null!=arguments[e]?arguments[e]:{};e%2?_t(Object(s),!0).forEach((function(e){n()(t,e,s[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(s)):_t(Object(s)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(s,e))}))}return t}x()(Ot,"components",{QSplitter:st["a"]});var wt={name:"Page.HOD.Metrics",data:function(){return{elements:[{name:"classes",label:"classes",component:S},{name:"mlo",label:"mlo",component:rt},{name:"history",label:"history",component:Mt}]}},computed:kt({},Object(l["c"])("user",["getGlobalSubject"])),methods:kt({},Object(l["d"])("hod",["setActiveYear"])),components:{toolbarPage:d["a"],YearBar:o["a"],ExamBar:c["a"]},created:function(){var t=this;this.setActiveYear(13),this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.setClass),this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.setClass)}},jt=wt,St=(s("eb8b"),Object(y["a"])(jt,a,r,!1,null,null,null));e["default"]=St.exports},d501:function(t,e,s){},df3b:function(t,e,s){"use strict";var a=s("773c"),r=s.n(a);r.a},eb8b:function(t,e,s){"use strict";var a=s("ef76"),r=s.n(a);r.a},ef76:function(t,e,s){}}]);