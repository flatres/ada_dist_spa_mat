(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[12],{"00d7":function(t,e,a){"use strict";var s=a("0d73"),i=a.n(s);i.a},"0d73":function(t,e,a){},"11ac":function(t,e,a){},1900:function(t,e,a){"use strict";var s=a("606a"),i=a.n(s);i.a},"503e":function(t,e,a){},5370:function(t,e,a){},"606a":function(t,e,a){},ada9:function(t,e,a){"use strict";var s=a("11ac"),i=a.n(s);i.a},d31e:function(t,e,a){"use strict";var s=a("5370"),i=a.n(s);i.a},f0d1:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.getGlobalSubject?a("div",[a("year-bar",{staticClass:"yb-border"}),a("exam-bar"),a("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),a("toolbar-page",{staticClass:"t-bar",attrs:{elements:t.elements,default:"classes"}})],1):t._e()},i=[],r=(a("e125"),a("4823"),a("2e73"),a("dde3"),a("76d0"),a("0c1f"),a("8e9e")),n=a.n(r),l=a("9ce4"),o=a("aba1"),c=a("89cf"),d=a("08e9"),u=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("loading",{attrs:{loading:t.loadingClasses}}),!t.loadingClasses&&t.show&&t.classes.length>0?a("div",{staticClass:"row no-wrap q-px-xs q-pt-xs text-left",staticStyle:{"padding-bottom":"200px"}},[a("div",{staticClass:"q-mr-md",staticStyle:{"min-width":"150px"}},[a("q-scroll-area",{staticStyle:{height:"80vh"}},[a("q-list",{staticClass:"no-padding"},t._l(t.classes,(function(e){return a("q-item",{key:e.code,staticClass:"q-pa-xs text-white bg-tertiary q-my-xs",class:e.id===t.selectedClassId?"active-class":"",staticStyle:{border:"1px solid grey"},attrs:{clickable:"",group:"classes",dense:""},on:{click:function(a){return t.setClass(e.id)}}},[a("q-item-section",{attrs:{top:""}},[a("q-item-label",{staticClass:"row"},[a("div",{staticClass:"col text-h7 text-boldx text-font q-mt-xs text-center"},[a("span",{staticClass:"q-mr-md"},[t._v(t._s(e.code))])])])],1)],1)})),1)],1)],1),a("div",{staticClass:"col"},[a("loading",{attrs:{loading:t.loading}}),t.loading?t._e():a("q-markup-table",{attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiaryx"},[a("th",{attrs:{colspan:"3"}}),t._l(t.teachers,(function(e){return a("th",{key:e.id,staticClass:"bg-tertiary text-center",attrs:{colspan:t.exams.length}},[t._v("\n              MLO - "+t._s(e.login)+"\n            ")])}))],2),a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-left"},[t._v("Name")]),a("th",{staticClass:"text-left"},[t._v("Class")]),a("th",{staticClass:"text-left"},[t._v("Boarding")]),t._l(t.teachers,(function(e){return a("th",{key:e.id,attrs:{colspan:t.exams.length}},[a("table",{staticClass:"full-width no-border"},t._l(t.exams,(function(e){return a("th",{key:e.id,staticClass:"text-center no-border"},[t._v(t._s(e.examCode)+"\n                ")])})),0)])}))],2)]),a("tbody",t._l(t.students,(function(e){return a("tr",{key:e.id},[a("td",{staticClass:"text-left"},[t._v(t._s(e.fullName))]),a("td",{staticClass:"text-left"},[t._v(t._s(t.classCode))]),a("td",{staticClass:"text-left"},[t._v(t._s(e.boardingHouse))]),t._l(t.teachers,(function(s){return a("td",{key:s.id,attrs:{colspan:t.exams.length}},[a("table",{staticClass:"full-width no-border"},t._l(t.exams,(function(i){return a("td",{key:i.id,staticClass:"text-center no-border"},[t._v(t._s(t.parseMLO(e,i,s)))])})),0)])}))],2)})),0)])],1)]):t._e()],1)},h=[],m=(a("288e"),a("4778")),f=a("452d"),g={getClassMetrics:function(t,e,a){var s=f["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+s.id+"/metrics/class/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMLO:function(t,e,a,s,i){var r=f["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+r.id+"/metrics/year/"+a+"/MLO/"+s,{cancelToken:i}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetrics:function(t,e,a,s,i){var r=f["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+r.id+"/metrics/year/"+a+"/metrics/"+s,{cancelToken:i}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetricsSpreadsheet:function(t,e,a,s,i){var r=f["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+r.id+"/metrics/year/"+a+"/metrics/"+s+"/spreadsheet",{cancelToken:i}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getExamHistory:function(t,e,a,s){var i=f["a"].getters["user/getGlobalSubject"];m["a"].get("/hod/"+i.id+"/metrics/year/"+a+"/history/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},p=a("268c"),b=a("10ac"),y={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,classes:[],selectedClassId:null,show:!1,exams:[],teachers:[],classCode:[],loading:!1,loadingClasses:!0}},methods:{processMetrics:function(t){this.exams=t.exams,this.teachers=t.teachers,this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.classCode=t.code,this.loading=!1},processClasses:function(t){this.classes=t,this.loadingClasses=!1,this.classes.length>0&&(this.show=!0,this.selectedClassId=this.classes[0].id,this.getClassMetrics())},getExamClasses:function(){this.loadingClasses=!0,p["a"].getExamClasses(this.processClasses,this.$errorFunction)},getMLO:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],g.getYearMLO(this.process,this.$errorHandler,this.year,this.exam)},setClass:function(t){this.selectedClassId=t,this.getClassMetrics()},getClassMetrics:function(){this.loading=!0,this.selectedClassId&&g.getClassMetrics(this.processMetrics,this.$errorHandler,this.selectedClassId)},parseMLO:function(t,e,a){var s=t.examData.mlo.find((function(t){return t.teacher.id===a.id&&t.examId===e.id}));return s.mlo?s.mlo:"."}},computed:{columns:function(){for(var t=[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"boarding",label:"Boarding",field:"boardingHouse",type:"string",align:"left",sortable:!0},{name:"mlo0",label:"MLO",field:"mlo0",type:"number",align:"left",sortable:!0}],e=1;e<this.maxMLOCount;e++){var a=e+1;t.push({name:"mlo"+e,label:"MLO "+a,field:"mlo"+e,type:"number",align:"left",sortable:!0,hidden:!1})}return t}},components:{Loading:b["a"]},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getExamClasses(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getExamClasses),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getExamClasses),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getExamClasses)},beforeDestroy:function(){this.subjectWatch(),this.yearWatch(),this.examWatch()}},v=y,x=(a("1900"),a("2be6")),M=a("e279"),C=a.n(M),k=a("26a8"),_=a("6c93"),O=a("ac9b"),q=a("66dc"),S=a("7d9a"),j=a("2e0b"),L=Object(x["a"])(v,u,h,!1,null,"381f390a",null),w=L.exports;C()(L,"components",{QScrollArea:k["a"],QList:_["a"],QItem:O["a"],QItemSection:q["a"],QItemLabel:S["a"],QMarkupTable:j["a"]});var G=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("loading",{attrs:{loading:t.loading,channel:"hod.metrics.mlo"}}),t.loading?t._e():a("q-splitter",{staticStyle:{height:"90vh"},scopedSlots:t._u([{key:"before",fn:function(){return[a("div",{staticClass:"q-pa-md"},[a("crud",{ref:"crud",attrs:{dataOverride:t.studentData,columns:t.columns,search:"",sortBy:"set",download:""}})],1)]},proxy:!0},{key:"after",fn:function(){return[a("div",{staticClass:"q-pa-md"},[a("profile-table",{attrs:{profile:t.subject.mloMaxGradeProfile,title:t.subject.maxMLOCount>1?"Max":""}}),a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n            Minimum MLO grade profile for all students and also including a gender breakdown\n          ")])],1),a("profile-pie",{attrs:{profile:t.subject.mloMaxGradeProfile}}),a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n            Maximum MLO grade profile for all students.\n          ")])],1),t.subject.maxMLOCount>1?a("profile-table",{attrs:{profile:t.subject.mloMinGradeProfile,title:"Min"}}):t._e(),t.subject.maxMLOCount>1?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n            Maximum MLO grade profile for all students and also including a gender breakdown\n          ")])],1):t._e(),t.subject.maxMLOCount>1?a("profile-pie",{attrs:{profile:t.subject.mloMinGradeProfile,title:"Max"}}):t._e(),t.subject.maxMLOCount>1?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n            Minimum MLO grade profile for all students.\n          ")])],1):t._e()],1)]},proxy:!0}],null,!1,3716565943),model:{value:t.splitter,callback:function(e){t.splitter=e},expression:"splitter"}})],1)},$=[],D=a("d612"),E=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-left"},[t._v(t._s(t.title))]),t._l(t.ordered,(function(e){return a("th",{key:e.grade},[t._v("\n        "+t._s("GCSE Avg"!==e.grade?e.grade:"")+"\n      ")])}))],2)]),a("tbody",[a("tr",[a("td",{staticClass:"text-left"},[t._v("All")]),t._l(t.ordered,(function(e){return a("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s("GCSE Avg"!==e.grade?e.count:"")+"\n      ")])}))],2),a("tr",[a("td",{staticClass:"text-left"},[t._v("Boys")]),t._l(t.ordered,(function(e){return a("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s(e.countM)+"\n      ")])}))],2),a("tr",[a("td",{staticClass:"text-left"},[t._v("Girls")]),t._l(t.ordered,(function(e){return a("td",{key:e.grade,staticClass:"text-center"},[t._v("\n        "+t._s(e.countF)+"\n      ")])}))],2)])])},P=[],W=(a("4fb0"),{name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{}},computed:{ordered:function(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},watch:{},components:{},created:function(){}}),N=W,A=Object(x["a"])(N,E,P,!1,null,"b8218506",null),T=A.exports;C()(A,"components",{QMarkupTable:j["a"]});var F=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"pie"})])},H=[],I=a("915b"),Y=a("3099"),B=a("d610");I["f"](B["a"]);var z={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{pie:null}},methods:{createPie:function(){var t=this.profileData;this.pie=I["c"](this.$refs.pie,Y["h"]);var e=this.pie,a=e.series.push(new Y["i"]);a.dataFields.value="count",a.dataFields.category="grade",e.innerRadius=I["e"](30),a.slices.template.stroke=I["b"]("#fff"),a.slices.template.strokeWidth=2,a.slices.template.strokeOpacity=1,a.slices.template.cursorOverStyle=[{property:"cursor",value:"pointer"}],a.alignLabels=!0,a.labels.template.bent=!0,a.labels.template.radius=3,a.labels.template.padding(0,0,0,0),a.ticks.template.disabled=!0;var s=a.slices.template.filters.push(new I["a"]);s.opacity=0;var i=a.slices.template.states.getKey("hover"),r=i.filters.push(new I["a"]);r.opacity=.7,r.blur=5,e.data=t}},computed:{profileData:function(){var t=[];return this.profile.forEach((function(e){"GCSE Avg"!==e.grade&&t.push(e)})),t}},watch:{},components:{},created:function(){},mounted:function(){this.createPie()},beforeDestroy:function(){this.pie.dispose()}},Q=z,R=Object(x["a"])(Q,F,H,!1,null,"4e711b94",null),K=R.exports,X=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"stacked",staticStyle:{height:"200px"}})])},J=[],U=(a("c880"),a("3903"));I["f"](U["a"]);var V={name:"HOD.Metrics.MLO.ProfileTable",props:{profile:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{stacked:null,chart:null}},methods:{createStacked:function(){this.stacked&&this.stacked.dispose();var t=I["c"](this.$refs.stacked,Y["l"]);t.colors.list=[I["b"]("#845EC2"),I["b"]("#D65DB1"),I["b"]("#FF6F91"),I["b"]("#FF9671"),I["b"]("#FFC75F"),I["b"]("#F9F871")],this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new Y["a"]);e.dataFields.category="type",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var a=t.yAxes.push(new Y["k"]);a.min=0,a.max=100,a.strictMinMax=!0,a.calculateTotals=!0,a.renderer.minWidth=50,t.data=this.stackData(),t.legend=new Y["f"]},addStackSeries:function(t,e){var a=t.series.push(new Y["c"]);a.sequencedInterpolation=!0,a.columns.template.width=I["e"](80),a.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",a.name=e,a.dataFields.categoryX="type",a.dataFields.valueY=e,a.dataFields.valueYShow="totalPercent",a.dataItems.template.locations.categoryX=.5,a.stacked=!0,a.tooltip.pointerOrientation="vertical"},stackData:function(){var t=this,e=[],a=[],s={},i={},r={};return this.profile.forEach((function(t){var a=t.grade;e.push(a),s.type="All",s[a]=t.count,i.type="Boys",i[a]=t.countM,r.type="Girls",r[a]=t.countF})),a.push(s),a.push(i),a.push(r),e.forEach((function(e){t.addStackSeries(t.stacked,e)})),a}},computed:{},watch:{},components:{},created:function(){},mounted:function(){this.createStacked()},beforeDestroy:function(){this.stacked.dispose()}},Z=V,tt=Object(x["a"])(Z,X,J,!1,null,"53fe8ee4",null),et=tt.exports,at={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0}},methods:{process:function(t){this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1},getMLO:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,g.getYearMLO(this.process,this.$errorFunction,this.year,this.exam)}},computed:{columns:function(){return this.subject.maxMLOCount>1?[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"Min MLO",field:"mloMin",type:"string",align:"left",sortable:!0},{name:"mloMax",label:"Max MLO",field:"mloMax",type:"string",align:"left",sortable:!0}]:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"set",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"MLO",field:"mloMin",type:"string",align:"left",sortable:!0}]},studentData:function(){var t=this,e=this.students.map((function(e){var a={id:e.id,displayName:e.displayName,classCode:e.classCode,schoolNumber:e.schoolNumber},s=e.exams.find((function(e){return e.id===t.exam}));return s&&(a.mloMin=s.mloMin,a.mloMax=s.mloMax),a}));return e}},watch:{},components:{Crud:D["a"],ProfileTable:T,Loading:b["a"],ProfilePie:K,ProfileStack:et},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getMLO(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getMLO),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getMLO),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getMLO)},beforeDestroy:function(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch(),this.examWatch&&this.examWatch()}},st=at,it=(a("ada9"),a("bc4f")),rt=a("34ff"),nt=a("3aaf"),lt=Object(x["a"])(st,G,$,!1,null,"176e1f66",null),ot=lt.exports;C()(lt,"components",{QSplitter:it["a"],QIcon:rt["a"],QTooltip:nt["a"]});var ct=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("loading",{attrs:{loading:t.loading,channel:"hod.metrics.history"}}),t.loading?t._e():a("history-table",{key:t.componentKey,attrs:{history:t.subject.history,grades:t.subject.grades,bands:t.subject.bands,bandedHistory:t.subject.bandedHistory}}),t.loading?t._e():a("history-stack",{key:t.componentKey,ref:"stack",attrs:{id:"1",profile:t.subject.stackedHistory,grades:t.subject.grades}})],1)},dt=[],ut=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"row"},[a("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{spread:"","no-caps":"",dense:"",outline:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.styles,year:"full-width"},model:{value:t.style,callback:function(e){t.style=e},expression:"style"}}),a("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{outline:"",spread:"","no-caps":"",dense:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.modes,year:"full-width"},model:{value:t.mode,callback:function(e){t.mode=e},expression:"mode"}})],1),"linear"===t.style?a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.grades,(function(e){return a("th",{key:e.grade},[t._v("\n          "+t._s(e.grade)+"\n          "),"GCSE Avg"===e.grade?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),a("th")],2)]),a("tbody",t._l(t.history,(function(e){return a("tr",{key:e.year},[a("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.grades,(function(s){return a("td",{key:s.grade,staticClass:"text-center"},[t._v("\n          "+t._s(t.getGrade(s.grade,e))+"\n        ")])})),a("td")],2)})),0)]):t._e(),"banded"===t.style?a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.bands,(function(e){return a("th",{key:e},[t._v("\n          "+t._s(e)+"\n          "),"GCSE Avg"===e?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),a("th")],2)]),a("tbody",t._l(t.bandedHistory,(function(e){return a("tr",{key:e.year},[a("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.bands,(function(s){return a("td",{key:s.bands,staticClass:"text-center"},[t._v("\n          "+t._s(t.getBandData(s,e))+"\n        ")])})),a("td",["Avg Last 3 Yrs"===e.year?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              Last 3 (if available) year's grades are treated as a single cohort with the final absolute values divided by 3.\n            ")])],1):t._e()],1)],2)})),0)]):t._e()],1)},ht=[],mt={name:"HOD.Metrics.MLO.ProfileTable",props:{history:{type:Array,required:!0},bandedHistory:{type:Array,required:!0},grades:{type:Array,required:!0},bands:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{mode:"percentage",modes:[{value:"percentage",label:"Percentage"},{value:"absolute",label:"Absolute"}],style:"banded",styles:[{value:"banded",label:"Banded"},{value:"linear",label:"Linear"}]}},computed:{ordered:function(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},methods:{getGrade:function(t,e){var a=e.results.find((function(e){return e.grade===t}));return a?"absolute"===this.mode||"GCSE Avg"===t?a.count:a.pct:"-"},getBandData:function(t,e){var a=e.results.find((function(e){return e.band===t}));if(!a)return"-";var s="absolute"===this.mode?a.abs:a.pct;return 0===s?"":s}},watch:{},components:{},created:function(){}},ft=mt,gt=a("6dd6"),pt=Object(x["a"])(ft,ut,ht,!1,null,"5191f8cd",null),bt=pt.exports;C()(pt,"components",{QBtnToggle:gt["a"],QMarkupTable:j["a"],QIcon:rt["a"],QTooltip:nt["a"]});var yt=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"stacked",staticStyle:{height:"200px"}})])},vt=[];a("ce9c"),a("cf91"),a("d2b6");I["f"](U["a"]);var xt={name:"HOD.Metrics.MLO.HistoryStack",props:{profile:{type:Array,required:!0},grades:{type:Array,required:!0},title:{tyle:String,default:""}},data:function(){return{stacked:null,chart:null}},methods:{createStacked:function(){var t=I["c"](this.$refs.stacked,Y["l"]);this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new Y["a"]);e.dataFields.category="year",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var a=t.yAxes.push(new Y["k"]);a.min=0,a.max=100,a.strictMinMax=!0,a.calculateTotals=!0,a.renderer.minWidth=50,t.data=this.stackData()},addStackSeries:function(t,e){var a=t.series.push(new Y["c"]);a.sequencedInterpolation=!0,a.columns.template.width=I["e"](80),a.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",a.name=e,a.dataFields.categoryX="year",a.dataFields.valueY=e,a.dataFields.valueYShow="totalPercent",a.dataItems.template.locations.categoryX=.5,a.stacked=!0,a.tooltip.pointerOrientation="vertical";var s=this.getColor(e);a.fill=I["b"](s)},getColor:function(t){var e="";switch(t){case"9":case"D1":e="#FF00FF";break;case"D2":case"A*":case"8":e="#9900ff";break;case"D3":case"A":case"7":e="#0000ff";break;case"M1":case"B":case"6":e="#4a86e8";break;case"M2":case"C":case"5":e="#00ffff";break;case"M3":case"D":case"4":e="#00ff00";break;case"P1":case"E":case"3":e="#ffff00";break;case"P2":case"2":e="#ff9900";break;case"U":e="#ff0000";break;default:e="#980000"}return e},stackData:function(){var t=this;return this.grades.forEach((function(e){Number.isInteger(e.grade)||t.stacked.colors.reset(),"GCSE Avg"!==e.grade&&t.addStackSeries(t.stacked,e.grade)})),this.profile}},computed:{},watch:{},components:{},created:function(){},mounted:function(){this.createStacked()},beforeDestroy:function(){this.stacked.dispose()}},Mt=xt,Ct=Object(x["a"])(Mt,yt,vt,!1,null,"5e19ad04",null),kt=Ct.exports,_t=a("8206"),Ot=a.n(_t),qt={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0,request:null,componentKey:1}},methods:{process:function(t){t.year===this.year&&t.id===this.subject.id&&t.examId===this.exam&&(this.componentKey++,this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1)},getHistory:function(){this.loading=!0,this.request&&this.request.cancel(),this.request=Ot.a.CancelToken.source(),this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.subject=this.$store.getters["user/getGlobalSubject"],g.getExamHistory(this.process,this.$errorFunction,this.year,this.exam,this.request)}},computed:{},watch:{},components:{HistoryTable:bt,HistoryStack:kt,Loading:b["a"]},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getHistory(),this.subWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getHistory),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getHistory),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getHistory)},beforeDestroy:function(){this.subWatch(),this.yearWatch(),this.examWatch()}},St=qt,jt=(a("f1e9"),Object(x["a"])(St,ct,dt,!1,null,"7c11e2db",null)),Lt=jt.exports,wt=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("loading",{attrs:{loading:t.loading,channel:"hod.metrics.metrics"}}),t.loading?t._e():a("div",{staticClass:"q-pa-md"},[a("crud",{ref:"crud",attrs:{dataOverride:t.studentData,columns:t.columns,search:"",sortBy:t.getSortBy}},[a("div",{attrs:{slot:"top-bar-left"},slot:"top-bar-left"},[a("q-btn",{staticClass:"q-mr-sm",attrs:{outline:"",color:"positive",icon:"fad fa-download"},on:{click:t.getSpreadSheet}})],1)])],1),a("q-dialog",{attrs:{persistent:"",maximized:t.maximizedToggle,"transition-show":"slide-up","transition-hide":"slide-down"},model:{value:t.controlsOpen,callback:function(e){t.controlsOpen=e},expression:"controlsOpen"}},[a("q-card",{staticClass:"bg-primary text-white"},[a("q-bar",[a("div",{staticClass:"text-h6"},[t._v("Weightings And Boundaries")]),a("q-space"),a("q-btn",{attrs:{dense:"",flat:"",icon:"minimize",disable:!t.maximizedToggle},on:{click:function(e){t.maximizedToggle=!1}}},[t.maximizedToggle?a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[t._v("Minimize")]):t._e()],1),a("q-btn",{attrs:{dense:"",flat:"",icon:"crop_square",disable:t.maximizedToggle},on:{click:function(e){t.maximizedToggle=!0}}},[t.maximizedToggle?t._e():a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[t._v("Maximize")])],1),a("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{dense:"",flat:"",icon:"close"}},[a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[t._v("Close")])],1)],1),a("q-card-section"),a("q-card-section",{staticClass:"q-pt-none"},[t._v("\n        Lorem ipsum dolor sit amet consectetur adipisicing elit. Rerum repellendus sit voluptate voluptas eveniet porro. Rerum blanditiis perferendis totam, ea at omnis vel numquam exercitationem aut, natus minima, porro labore.\n      ")])],1)],1)],1)},Gt=[],$t={name:"HOD.Data.MLO",props:{},data:function(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:100,loading:!0,metrics:!1,controlsOpen:!1,maximizedToggle:!1}},methods:{process:function(t){this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.metrics=t.metrics,this.loading=!1},getMetrics:function(){this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,g.getYearMetrics(this.process,this.$errorFunction,this.year,this.exam)},openControls:function(){},getSpreadSheet:function(){var t=this;this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,g.getYearMetricsSpreadsheet((function(e){t.loading=!1,t.$downloadBlob(e.url,e.file)}),(function(e){t.loading=!1}),this.year,this.exam)}},computed:{columns:function(){var t;return t=this.subject.maxMLOCount>1?[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"Min MLO",field:"mloMin",type:"string",align:"left",sortable:!0},{name:"mloMax",label:"Max MLO",field:"mloMax",type:"string",align:"left",sortable:!0}]:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!1},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"schNumber",label:"Sch. #",field:"schoolNumber",type:"string",align:"left",sortable:!0},{name:"mloMin",label:"MLO",field:"mloMin",type:"string",align:"left",sortable:!0}],this.subject.year>11?(t.push({name:"alisScore",label:"Alis",field:"alisBaseline",type:"string",align:"left",sortable:!0}),t.push({name:"alisPrediction",label:"Prediction",field:"alisPrediction",type:"string",align:"left",sortable:!0})):(t.push({name:"midyisScore",label:"Mdys",field:"midyisBaseline",type:"string",align:"left",sortable:!0}),t.push({name:"midyisPrediction",label:"Grade",field:"midyisPrediction",type:"string",align:"left",sortable:!0})),this.metrics?(this.metrics.GCSEMock.length>0&&(t.push({name:"gcseMockGrade",label:"GCSE Mock",field:"gcseMockGrade",type:"string",align:"left",sortable:!0}),t.push({name:"gcseMockPct",label:"%",field:"gcseMockPercentage",type:"string",align:"left",sortable:!0}),t.push({name:"gcseMockRank",label:"Rnk",field:"gcseMockRank",type:"string",align:"left",sortable:!0}),t.push({name:"gcseMockGpa",label:"Gpa",field:"gcseMockGpa",type:"string",align:"left",sortable:!0})),this.metrics.GCSE.length>0&&(t.push({name:"gcseGrade",label:"GCSE",field:"gcseGrade",type:"string",align:"left",sortable:!0}),t.push({name:"gcseGpa",label:"Gpa",field:"gcseGpa",type:"string",align:"left",sortable:!0})),this.metrics.IGDR.length,this.metrics.ALevelMock.length>0&&(t.push({name:"aLevelMockGrade",label:"U6 Mock",field:"aLevelMockGrade",type:"string",align:"left",sortable:!0}),t.push({name:"aLevelMockPercentage",label:"%",field:"aLevelMockPercentage",type:"string",align:"left",sortable:!0}),t.push({name:"aLevelMockRank",label:"Rank",field:"aLevelMockRank",type:"string",align:"left",sortable:!0})),t):t},getSortBy:function(){return this.metrics&&this.metrics.ALevelMock.length>0?"aLevelMockRank":"set"},studentData:function(){var t=this,e=this.students.map((function(e){var a={id:e.id,displayName:e.displayName,classCode:e.classCode,schoolNumber:e.schoolNumber,midyisBaseline:e.midyisBaseline,midyisPrediction:e.midyisPrediction,alisPrediction:e.alisGcsePrediction,alisBaseline:e.alisGcseBaseline,gcseGpa:e.gcseGpa,gcseMockGpa:e.gcseMockGpa},s=e.exams.find((function(e){return e.id===t.exam}));if(s&&(a.mloMin=s.mloMin,a.mloMax=s.mloMax),!t.metrics)return a;var i=t.metrics.GCSEMock.find((function(t){return t.studentId===a.id}));i&&(a.gcseMockGrade=i.grade,a.gcseMockRank=i.cohortRank,a.gcseMockPercentage=i.percentage);var r=t.metrics.GCSE.find((function(t){return t.studentId===a.id}));r&&(a.gcseGrade=r.grade);var n=t.metrics.ALevelMock.find((function(t){return t.studentId===a.id}));n&&(a.aLevelMockGrade=n.grade,a.aLevelMockRank=n.cohortRank,a.aLevelMockPercentage=n.percentage);var l=t.metrics.IGDR.find((function(t){return t.studentId===a.id}));return l&&(a.gcseDelta=l.gcseDelta,a.IGDR=l.IGDR),a}));return e}},watch:{},components:{Crud:D["a"],Loading:b["a"]},created:function(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getMetrics(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getMetrics),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getMetrics),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getMetrics)},beforeDestroy:function(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch(),this.examWatch&&this.examWatch()}},Dt=$t,Et=(a("00d7"),a("2ef0")),Pt=a("e81c"),Wt=a("ebe6"),Nt=a("5d16"),At=a("f962c"),Tt=a("965d"),Ft=a("58c0"),Ht=Object(x["a"])(Dt,wt,Gt,!1,null,"4ba8ab09",null),It=Ht.exports;function Yt(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function Bt(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?Yt(Object(a),!0).forEach((function(e){n()(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):Yt(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}C()(Ht,"components",{QBtn:Et["a"],QDialog:Pt["a"],QCard:Wt["a"],QBar:Nt["a"],QSpace:At["a"],QTooltip:nt["a"],QCardSection:Tt["a"]}),C()(Ht,"directives",{ClosePopup:Ft["a"]});var zt={name:"Page.HOD.Metrics",data:function(){return{elements:[{name:"classes",label:"classes",component:w},{name:"mlo",label:"mlo",component:ot},{name:"history",label:"history",component:Lt},{name:"metrics",label:"Metrics",component:It}]}},computed:Bt({},Object(l["c"])("user",["getGlobalSubject"])),methods:Bt({},Object(l["d"])("hod",["setActiveYear"])),components:{toolbarPage:d["a"],YearBar:o["a"],ExamBar:c["a"]},created:function(){var t=this;this.setActiveYear(13),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.setClass),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.setClass)},beforeDestroy:function(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch()}},Qt=zt,Rt=(a("d31e"),Object(x["a"])(Qt,s,i,!1,null,null,null));e["default"]=Rt.exports},f1e9:function(t,e,a){"use strict";var s=a("503e"),i=a.n(s);i.a}}]);