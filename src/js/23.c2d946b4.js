(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[23],{"114f":function(t,e,a){"use strict";var s=a("fbb2"),r=a.n(s);r.a},"8d85":function(t,e,a){"use strict";var s=a("fa4e"),r=a.n(s);r.a},e4b7:function(t,e,a){},f0d1:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return t.getGlobalSubject?a("div",[a("div",{staticClass:"full-width bg-secondary",staticStyle:{height:"1px"}}),a("toolbar-page",{staticClass:"t-bar",attrs:{elements:t.elements,default:"metrics"},scopedSlots:t._u([{key:"before",fn:function(){return[a("year-bar",{staticClass:"yb-border",attrs:{exams:""}})]},proxy:!0}],null,!1,311916022)})],1):t._e()},r=[],n=(a("e125"),a("4823"),a("2e73"),a("76d0"),a("7f3a")),i=a.n(n),o=a("8e9e"),c=a.n(o),l=a("9ce4"),d=a("aba1"),u=a("89cf"),h=a("08e9"),g=a("9489"),f=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("loading",{attrs:{loading:t.loading,channel:"hod.metrics.history"}}),t.loading?t._e():a("history-table",{key:t.componentKey,attrs:{history:t.subject.history,grades:t.subject.grades,bands:t.subject.bands,bandedHistory:t.subject.bandedHistory}}),t.loading?t._e():a("history-stack",{key:t.componentKey,ref:"stack",attrs:{id:"1",profile:t.subject.stackedHistory,grades:t.subject.grades}})],1)},m=[],p=a("47783"),b=a("452d"),y={getClassMetrics(t,e,a){var s=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+s.id+"/metrics/class/"+a).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMLO(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/MLO/"+s,{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetrics(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/metrics/"+s,{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getYearMetricsSpreadsheet(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/metrics/"+s+"/spreadsheet",{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getPDFByClass(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/metrics/"+s+"/pdf/class",{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getPDFByName(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/metrics/"+s+"/pdf/name",{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getPDFBlank(t,e,a,s,r){var n=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+n.id+"/metrics/year/"+a+"/metrics/"+s+"/pdf/blank",{cancelToken:r}).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))},getExamHistory(t,e,a,s){var r=b["a"].getters["user/getGlobalSubject"];p["a"].get("/hod/"+r.id+"/metrics/year/"+a+"/history/"+s).then((function(e){t(e.data)})).catch((function(t){console.log(t)}))}},v=a("10ac"),w=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{staticClass:"row"},[a("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{spread:"","no-caps":"",dense:"",outline:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.styles,year:"full-width"},model:{value:t.style,callback:function(e){t.style=e},expression:"style"}}),a("q-btn-toggle",{staticClass:"col q-ml-xs",attrs:{outline:"",spread:"","no-caps":"",dense:"","toggle-color":"tertiary",color:"secondary","text-color":"font-secondary","toggle-text-color":"accent",options:t.modes,year:"full-width"},model:{value:t.mode,callback:function(e){t.mode=e},expression:"mode"}})],1),"linear"===t.style?a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.grades,(function(e){return a("th",{key:e.grade},[t._v("\n          "+t._s(e.grade)+"\n          "),"GCSE Avg"===e.grade?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),a("th")],2)]),a("tbody",t._l(t.history,(function(e){return a("tr",{key:e.year},[a("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.grades,(function(s){return a("td",{key:s.grade,staticClass:"text-center"},[t._v("\n          "+t._s(t.getGrade(s.grade,e))+"\n        ")])})),a("td")],2)})),0)]):t._e(),"banded"===t.style?a("q-markup-table",{staticClass:"q-mt-xs q-my-md",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{staticClass:"text-right"},[t._v(t._s("percentage"===t.mode?"%":"Abs"))]),t._l(t.bands,(function(e){return a("th",{key:e},[t._v("\n          "+t._s(e)+"\n          "),"GCSE Avg"===e?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              The average GCSE grades of the pupils taking the subject in a given year.\n            ")])],1):t._e()],1)})),a("th")],2)]),a("tbody",t._l(t.bandedHistory,(function(e){return a("tr",{key:e.year},[a("td",{staticClass:"text-right"},[t._v(t._s(e.year))]),t._l(t.bands,(function(s){return a("td",{key:s.bands,staticClass:"text-center"},[t._v("\n          "+t._s(t.getBandData(s,e))+"\n        ")])})),a("td",["Avg Last 3 Yrs"===e.year?a("q-icon",{attrs:{size:"xs",color:"font",name:"fad fa-question-circle"}},[a("q-tooltip",{attrs:{"content-class":"bg-tertiary"}},[t._v("\n              Last 3 (if available) year's grades are treated as a single cohort with the final absolute values divided by 3.\n            ")])],1):t._e()],1)],2)})),0)]):t._e()],1)},x=[],k=(a("4fb0"),{name:"HOD.Metrics.MLO.ProfileTable",props:{history:{type:Array,required:!0},bandedHistory:{type:Array,required:!0},grades:{type:Array,required:!0},bands:{type:Array,required:!0},title:{tyle:String,default:""}},data(){return{mode:"percentage",modes:[{value:"percentage",label:"Percentage"},{value:"absolute",label:"Absolute"}],style:"banded",styles:[{value:"banded",label:"Banded"},{value:"linear",label:"Linear"}]}},computed:{ordered(){return parseInt(this.profile[0])<7?this.profile.split().reverse():this.profile}},methods:{getGrade(t,e){var a=e.results.find((function(e){return e.grade===t}));return a?"absolute"===this.mode||"GCSE Avg"===t?a.count:a.pct:"-"},getBandData(t,e){var a=e.results.find((function(e){return e.band===t}));if(!a)return"-";var s="absolute"===this.mode?a.abs:a.pct;return 0===s?"":s}},watch:{},components:{},created(){}}),_=k,C=a("2be6"),S=a("6dd6"),q=a("2e0b"),j=a("34ff"),D=a("3aaf"),M=a("e279"),G=a.n(M),O=Object(C["a"])(_,w,x,!1,null,"5191f8cd",null),P=O.exports;G()(O,"components",{QBtnToggle:S["a"],QMarkupTable:q["a"],QIcon:j["a"],QTooltip:D["a"]});var W=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("div",{ref:"stacked",staticStyle:{height:"200px"}})])},I=[],T=(a("c880"),a("915b")),$=a("3099"),B=a("3903");T["f"](B["a"]);var A={name:"HOD.Metrics.MLO.HistoryStack",props:{profile:{type:Array,required:!0},grades:{type:Array,required:!0},title:{tyle:String,default:""}},data(){return{stacked:null,chart:null}},methods:{createStacked(){var t=T["c"](this.$refs.stacked,$["l"]);this.stacked=t,t.hiddenState.properties.opacity=0,t.colors.step=4,t.padding(10,10,10,10);var e=t.xAxes.push(new $["a"]);e.dataFields.category="year",e.renderer.grid.template.location=0,e.renderer.minGridDistance=50;var a=t.yAxes.push(new $["k"]);a.min=0,a.max=100,a.strictMinMax=!0,a.calculateTotals=!0,a.renderer.minWidth=50,t.data=this.stackData()},addStackSeries(t,e){var a=t.series.push(new $["c"]);a.sequencedInterpolation=!0,a.columns.template.width=T["e"](80),a.columns.template.tooltipText="[bold]{name}[/][font-size:14px]: {valueY.totalPercent.formatNumber('#.00')}%",a.name=e,a.dataFields.categoryX="year",a.dataFields.valueY=e,a.dataFields.valueYShow="totalPercent",a.dataItems.template.locations.categoryX=.5,a.stacked=!0,a.tooltip.pointerOrientation="vertical";var s=this.getColor(e);a.fill=T["b"](s)},getColor(t){var e="";switch(t){case"9":case"D1":e="#FF00FF";break;case"D2":case"A*":case"8":e="#9900ff";break;case"D3":case"A":case"7":e="#0000ff";break;case"M1":case"B":case"6":e="#4a86e8";break;case"M2":case"C":case"5":e="#00ffff";break;case"M3":case"D":case"4":e="#00ff00";break;case"P1":case"E":case"3":e="#ffff00";break;case"P2":case"2":e="#ff9900";break;case"U":e="#ff0000";break;default:e="#980000"}return e},stackData(){var t=this;return this.grades.forEach((function(e){Number.isInteger(e.grade)||t.stacked.colors.reset(),"GCSE Avg"!==e.grade&&t.addStackSeries(t.stacked,e.grade)})),this.profile}},computed:{},watch:{},components:{},created(){},mounted(){this.createStacked()},beforeDestroy(){this.stacked.dispose()}},E=A,L=Object(C["a"])(E,W,I,!1,null,"5e19ad04",null),Y=L.exports,H=a("8206"),R=a.n(H),Q={name:"HOD.Data.MLO",props:{},data(){return{year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:60,loading:!0,request:null,componentKey:1}},methods:{process(t){t.year===this.year&&t.id===this.subject.id&&t.examId===this.exam&&(this.componentKey++,this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.loading=!1)},getHistory(){this.loading=!0,this.request&&this.request.cancel(),this.request=R.a.CancelToken.source(),this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.subject=this.$store.getters["user/getGlobalSubject"],y.getExamHistory(this.process,this.$errorFunction,this.year,this.exam,this.request)}},computed:{},watch:{},components:{HistoryTable:P,HistoryStack:Y,Loading:v["a"]},created(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getHistory(),this.subWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getHistory),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getHistory),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getHistory)},beforeDestroy(){this.subWatch(),this.yearWatch(),this.examWatch()}},F=Q,N=(a("f1e9"),Object(C["a"])(F,f,m,!1,null,"7c11e2db",null)),z=N.exports,U=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"q-pr-sm col row"},[t.loading?a("loading",{staticClass:"full-width",attrs:{loading:t.loading,channel:"hod.metrics.metrics"}}):t._e(),t.loading?t._e():a("div",{staticClass:"col column ada-table"},[a("q-bar",{staticClass:"bg-tertiary full-width bd-font-secondary overflow-hidden q-pl-sm"},[a("q-input",{staticStyle:{width:"300px"},attrs:{filled:"",dense:""},scopedSlots:t._u([{key:"before",fn:function(){return[a("q-icon",{attrs:{name:"search"}})]},proxy:!0}],null,!1,2736547555),model:{value:t.searchTerm,callback:function(e){t.searchTerm=e},expression:"searchTerm"}}),a("q-btn",{attrs:{color:"positive",flat:"",icon:"fad fa-download",labelX:"sheet",styleX:"width:200px"},on:{click:t.getSpreadSheet}}),a("q-toggle",{staticClass:"text-font q-mr-md",staticStyle:{"font-size":"10px"},attrs:{label:"Baseline Data","left-label":"",dense:"",size:"sm",color:"accent"},model:{value:t.showBaselineData,callback:function(e){t.showBaselineData=e},expression:"showBaselineData"}}),a("q-toggle",{staticClass:"text-font q-mr-md",staticStyle:{"font-size":"10px"},attrs:{label:"Show Grades","left-label":"",dense:"",size:"sm",color:"accent"},model:{value:t.showGrades,callback:function(e){t.showGrades=e},expression:"showGrades"}}),a("q-toggle",{staticClass:"text-font",staticStyle:{"font-size":"10px"},attrs:{label:"Meta Data","left-label":"",dense:"",size:"sm",color:"accent"},model:{value:t.showMetaData,callback:function(e){t.showMetaData=e},expression:"showMetaData"}}),a("q-space"),a("q-btn",{attrs:{color:"positive",flat:"",icon:"fad fa-file-pdf",labelX:"sheet",styleX:"width:200px"},on:{click:function(e){t.pdfSelectOpen=!0}}}),t.selectedWyapId?a("q-btn",{attrs:{icon:t.swarmOpen?"fas fa-arrow-right":"fas fa-arrow-left",disable:!t.selectedWyapId,color:"font",flat:"",dense:""},on:{click:function(e){t.swarmOpen=!t.swarmOpen}}}):t._e()],1),a("div",{staticClass:"col column q-mt-md",staticStyle:{"min-width":"300px"}},[a("q-scroll-area",{staticClass:"column col q-pb-xl"},[a("q-markup-table",{staticClass:"q-mb-xl q-pb-md",staticStyle:{"max-width":"100%","white-space":"nowrap"},attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-mb-md"},[a("tr",{staticClass:"bg-tertiary"},[a("th",{attrs:{colspan:t.columnCount,align:"left"}},[t.gcseGPA>0?a("span",[t._v("GCSE GPA: "+t._s(t.gcseGPA))]):t._e()]),t.subject.wyaps.length>0?a("th",{staticClass:"text-positive",attrs:{colspan:"2"},on:{click:function(e){return t.setWyapSort("totals")}}},[t._v("\n                    Aggregate\n                    "),a("q-icon",{staticClass:"q-ml-xs",attrs:{name:"fal fa-arrows-alt-v",color:"accent"}})],1):t._e(),t._l(t.subject.wyaps,(function(e,s){return a("th",{key:e.id,staticClass:"text-center",attrs:{colspan:"2"},on:{click:function(a){return t.setWyapSort(e.id)}}},[t._v("\n                      "+t._s(e.typeShort)+" "+t._s(t.subject.wyaps.length-s)+"\n                      "),a("q-icon",{staticClass:"q-ml-xs",attrs:{name:"fal fa-arrows-alt-v",color:"accent"}}),a("q-tooltip",[t._v(t._s(e.name))])],1)})),a("th",{staticClass:"absorbing-column"})],2),a("tr",{staticClass:"bg-tertiary"},[t._l(t.columns,(function(e){return e.hidden?t._e():a("th",{key:e.name,staticClass:"text-left",on:{click:function(a){return t.setSort(e.field)}}},[t._v("\n                      "+t._s(e.label)+"\n                      "),a("q-icon",{staticClass:"q-ml-xs",attrs:{name:"fal fa-arrows-alt-v",color:"accent"}})],1)})),t.subject.wyaps.length>0?a("th",{staticClass:"text-center",on:{click:function(e){return t.setWyap("totals")}}},[t._v("%\n                      ")]):t._e(),t.subject.wyaps.length>0?a("th",{staticClass:"text-center",on:{click:function(e){return t.setWyap("totals")}}},[t._v("Rnk\n                    ")]):t._e(),t._l(t.subject.wyaps,(function(e){return[a("th",{key:e.id+"_",staticClass:"text-center",on:{click:function(a){return t.setWyap(e.id)}}},[t._v(t._s(t.showGrades&&e.hasGrades?"Gd":"%")+"\n                        ")]),a("th",{key:e.id,staticClass:"text-center",on:{click:function(a){return t.setWyap(e.id)}}},[t._v(t._s(t.showMetaData?"":"Rnk")+"\n                        ")])]})),a("th",{staticClass:"absorbing-column"})],2)]),a("tbody",t._l(t.visibleStudents,(function(e){return a("tr",{key:e.id,class:{"bg-font-secondary text-font-negative":e.id===t.selectedStudentId},on:{click:function(a){t.selectedStudentId=e.id}}},[t._l(t.columns,(function(s){return s.hidden?t._e():a("td",{key:s.name,staticClass:"text-left"},[t._v("\n                      "+t._s(e[s.field])+"\n                    ")])})),t.subject.wyaps.length>0?a("td",{staticClass:"text-center",class:{"bg-tertiary":e.id!==t.selectedStudentId&&"totals"!==t.selectedWyapId,"bg-font-secondary text-font-negative":"totals"===t.selectedWyapId},on:{click:function(a){return t.setWyap("totals",e.id)}}},[t._v("\n                        "+t._s(t.wyapTotalsPct(e.id))+"\n                    ")]):t._e(),t.subject.wyaps.length>0?a("td",{staticClass:"text-center",class:{"bg-tertiary":e.id!==t.selectedStudentId&&"totals"!==t.selectedWyapId,"bg-font-secondary text-font-negative":"totals"===t.selectedWyapId},on:{click:function(a){return t.setWyap("totals",e.id)}}},[t._v("\n                        "+t._s(t.wyapTotalsRank(e.id))+"\n                    ")]):t._e(),t._l(t.subject.wyaps,(function(s,r){return[a("td",{key:s.id+"_",staticClass:"text-center",class:{"bg-tertiary":r%2===1&&e.id!==t.selectedStudentId&&s.id!==t.selectedWyapId,"bg-font-secondary text-font-negative":s.id===t.selectedWyapId},on:{click:function(a){return t.setWyap(s.id,e.id)}}},[t._v("\n                        "+t._s(t.showGrades&&s.hasGrades?t.wyapGrade(e.id,s.id):t.wyapPct(e.id,s.id))+"\n                    ")]),t.showMetaData?t._e():a("td",{key:s.id,staticClass:"text-center",class:{"bg-tertiary":r%2===1&&e.id!==t.selectedStudentId&&s.id!==t.selectedWyapId,"bg-font-secondary text-font-negative":s.id===t.selectedWyapId},on:{click:function(a){return t.setWyap(s.id,e.id)}}},[t._v("\n                        "+t._s(t.wyapRank(e.id,s.id))+"\n                    ")]),t.showMetaData?a("td",{key:s.id,staticClass:"text-left",class:{"bg-tertiary":r%2===1&&e.id!==t.selectedStudentId&&s.id!==t.selectedWyapId,"bg-font-secondary text-font-negative":s.id===t.selectedWyapId},on:{click:function(a){return t.setWyap(s.id,e.id)}}},[t.wyapExtraTime(e.id,s.id)?a("q-badge",{staticClass:"q-mr-xs",attrs:{color:"positive"}},[a("q-icon",{attrs:{name:"far fa-clock",color:"primary"}}),a("q-tooltip",{staticClass:"bg-font-secondary"},[t._v("Used Extra Time")])],1):t._e(),t.wyapUnderperformed(e.id,s.id)?a("q-badge",{staticClass:"q-mr-xs",attrs:{color:"warning"}},[a("q-icon",{attrs:{name:"far fa-thumbs-down",color:"white"}}),a("q-tooltip",{staticClass:"bg-font-secondary"},[t._v("Underperformed")])],1):t._e(),t.wyapComment(e.id,s.id)?a("q-badge",{attrs:{color:"positive",outline:""}},[a("q-icon",{attrs:{name:"far fa-sticky-note",color:"positive"}}),a("q-tooltip",{staticClass:"bg-font-secondary"},[t._v(t._s(t.wyapComment(e.id,s.id)))])],1):t._e()],1):t._e()]})),a("td",{staticClass:"absorbing-column"})],2)})),0)])],1)],1)],1),t.showSwarm?a("div",{staticClass:"row column",staticStyle:{"max-width":"300px",width:"300px"}},[a("swarm",{attrs:{results:t.swarmResults,title:t.swarmTitle,selectedStudentId:t.selectedStudentId},on:{fullscreen:function(e){t.classTableOpen=!0}}})],1):t._e(),a("q-dialog",{attrs:{maximized:""},model:{value:t.classTableOpen,callback:function(e){t.classTableOpen=e},expression:"classTableOpen"}},[a("q-card",{staticClass:"full-width column bg-primary",staticStyle:{height:"100vh"}},[a("classTable",{staticClass:"col column full-width",attrs:{title:t.swarmTitle,"full-screen":"",results:t.swarmResults,wyap:t.wyap},on:{close:function(e){t.classTableOpen=!1}}})],1)],1),a("q-dialog",{model:{value:t.pdfSelectOpen,callback:function(e){t.pdfSelectOpen=e},expression:"pdfSelectOpen"}},[a("q-card",{staticClass:"full-width column bg-primary bd-font-secondary"},[a("q-card-section",[a("h5",{staticClass:"text-font"},[t._v("Download TAG Cover Sheets")]),a("p",{staticClass:"text-font"},[t._v("How would you like your document organised?")]),a("div",{staticClass:"row"},[a("q-btn",{staticClass:"a-btn col on-left",attrs:{label:"By Class"},on:{click:t.getPDFByClass}}),a("q-btn",{staticClass:"a-btn col",attrs:{label:"By Name"},on:{click:t.getPDFByName}})],1),a("div",{staticClass:"row"},[a("q-btn",{staticClass:"a-btn col-12 q-mt-md",attrs:{label:"Download A Blank Sheet"},on:{click:t.getPDFBlank}})],1)])],1)],1)],1)},K=[],X=(a("632c"),function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"q-pr-sm q-ml-sm col column"},[a("loading",{attrs:{loading:t.loading,message:"Crunching some numbers..."}}),!t.loading&&t.results.length>0?a("div",{staticClass:"row col column"},[a("div",{ref:"scrollzone",staticClass:"col q-mt-none column",attrs:{id:"scrollzone"}},[a("q-scroll-area",{staticClass:"column col"},[a("q-markup-table",{staticClass:"q-pb-sm col column bg-primary bd-font",attrs:{dense:"",flat:"",bordered:""}},[a("thead",{staticClass:"q-pb-md"},[a("tr",{staticClass:"bg-tertiary",staticStyle:{"max-height":"8px",height:"8px","overflow-y":"hidden"}},t._l(t.data,(function(e){return a("th",{key:e.name,staticClass:"text-center"},[a("q-bar",{staticClass:"bg-tertiary q-px-none",staticStyle:{"font-size":"13px"}},[t._v("\n                  "+t._s(e.name)+"\n                  "),a("q-space"),a("q-btn",{attrs:{icon:"fas fa-expand-arrows-alt",flat:"",color:"positive",size:"xs",dense:""},on:{click:function(e){return t.$emit("fullscreen")}}})],1)],1)})),0)]),a("tbody",{staticClass:"bg-primary q-mt-md",staticStyle:{"font-size":"5px"}},t._l(101,(function(e){return a("tr",{key:e,staticClass:"ct-row db-primary ct-border"},t._l(t.data,(function(s){return a("td",{key:s.name,staticClass:"ct-row ct-border text-center bg-primary",class:{"bg-secondary":(102-e)%10==0&&!t.isYearAverage(102-e,s),"bg-accent-secondary":t.isYearAverage(102-e,s),"":!t.isYearAverage(102-e,s)},style:t.rowStyle},[t._l(t.getPctResults(s,102-e),(function(e){return"names"===t.mode?a("span",{key:e.id,staticClass:"q-mx-xs",class:t.nameClass(e)},[t._v(t._s(e.lastName))]):t._e()})),t._l(t.getPctResults(s,102-e),(function(e){return"names"!==t.mode&&e.id===t.selectedStudentId?a("q-spinner-facebook",{key:e.id,attrs:{color:"positive",size:"10px"}}):t._e()})),t._l(t.getPctResults(s,102-e),(function(e){return"names"!==t.mode&&e.id!==t.selectedStudentId?a("q-btn",{key:e.id,staticClass:"q-pa-none",class:{blink:e.id===t.selectedStudentId},staticStyle:{"max-height":"1px",height:"1px","font-size":"8px","padding-top":"0px","padding-bottom":"0px","margin-left":"1px","margin-right":"1px"},attrs:{color:t.badgeColor(e),"text-color":t.badgeColor(e),label:t.badgeLabel(e),size:"xs",dense:""},on:{click:function(a){return t.showResult(e)}}},[t._v(".\n                  "),a("q-tooltip",{attrs:{"content-class":"bg-font-secondary"}},[a("p",{staticClass:"text-bold"},[t._v(t._s(e.name))]),a("p",[t._v(t._s(e.pct)+"%")])])],1):t._e()})),t.isYearAverage(102-e,s)?a("q-tooltip",[t._v("Class Average "+t._s(s.averagePct)+"%")]):t._e()],2)})),0)})),0)])],1)],1)]):t._e()],1)}),J=[],V={name:"hods.metrics.wyaps.results",props:{results:{type:Array,required:!0},title:{type:String,required:!0},fullScreen:{type:Boolean},selectedStudentId:{type:Number}},data(){return{mode:"flagged",data:[],loading:!1,modeOptions:[{icon:"fas fa-flag",value:"flagged",slot:"flagged"},{icon:"fas fa-venus-mars",value:"gender",slot:"gender"},{icon:"fas fa-user",value:"names",slot:"names"}],meanPct:0,mounted:!1}},methods:{getPctResults(t,e){return e<1||e>100?[]:t.results.find((function(t){return t.pct===e})).results},nameClass(t){return t.hasUnderperformed?"text-warning":"font-secondary"},badgeColor(t){return t.id===this.selectedStudentId?"positive":"gender"===this.mode?"F"===t.gender?"red-3":"blue-4":t.hasUnderperformed?"warning":"font"},badgeLabel(t){return"names"===this.mode?t.lastName:"."},rowBgColor(t){return Math.round(t)===Math.round(this.meanPct)?"bg-accent-secondary ct-border":"bg-secondary ct-border"},isYearAverage(t){return Math.round(t)===Math.round(this.meanPct)},makeData(){this.loading=!0;for(var t=[],e=this,a=0,s=0,r={name:this.title,results:[],averagePct:0},n=100;n>0;n--){var i=e.results.filter((function(t){return Math.round(t.pct)===n})),o={pct:n,results:i};a+=n*i.length,s+=i.length,r.results.push(o)}s>0&&(r.averagePct=Math.round(a/s)),t.push(r),this.data=t,this.loading=!1}},computed:{visible(){return this.results},sorted(){var t=this.visible.filter((function(t){return t.id>0}));return t.sort(this.$compare(this.sortKey,this.sortDir))},rowStyle(){if(!this.mounted)return{};var t="4px!important",e={"line-height":t,"max-height":t,height:t,"font-size":t};return e}},watch:{results:{deep:!0,handler(){this.makeData()}},selectedStudentId(t){this.makeData()}},components:{Loading:v["a"]},created(){this.makeData()},mounted(){this.mounted=!0}},Z=V,tt=(a("114f"),a("bc74")),et=a("26a8"),at=a("5d16"),st=a("f962c"),rt=a("2ef0"),nt=a("3972"),it=a("dfd0"),ot=Object(C["a"])(Z,X,J,!1,null,null,null),ct=ot.exports;G()(ot,"components",{QInput:tt["a"],QScrollArea:et["a"],QMarkupTable:q["a"],QBar:at["a"],QSpace:st["a"],QBtn:rt["a"],QSpinnerFacebook:nt["a"],QTooltip:D["a"],QColor:it["a"]});var lt=a("f21d"),dt={name:"HOD.Data.MLO",props:{},data(){return{pdfSelectOpen:!1,searchTerm:"",year:null,exam:null,subject:null,students:[],maxMLOCount:4,splitter:100,loading:!0,metrics:!1,controlsOpen:!1,maximizedToggle:!1,sortKey:"displayName",sortDir:"asc",splitterModel:80,swarmResults:[],swarmTitle:"",selectedStudentId:null,selectedWyapId:null,showBaselineData:!1,showGrades:!1,showMetaData:!1,swarmOpen:!0,classTableOpen:!1,wyap:{},gcseGPA:0}},methods:{setSort(t){t===this.sortKey&&(this.sortDir="asc"===this.sortDir?"desc":"asc"),this.sortKey=t},setWyapSort(t){var e="wyap_"+t+"_rank";this.selectedWyapId=t,e===this.sortKey&&(this.sortDir="asc"===this.sortDir?"desc":"asc"),this.sortKey=e},process(t){this.students=t.students,this.maxMLOCount=t.maxMLOCount,this.subject=t,this.metrics=t.metrics,this.loading=!1,this.gcseGPA=t.gcseGPA},getMetrics(){this.selectedWyapId=null,this.selectedStudentId=null,this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,y.getYearMetrics(this.process,this.$errorFunction,this.year,this.exam)},openControls(){},getSpreadSheet(){var t=this;this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,y.getYearMetricsSpreadsheet((function(e){t.loading=!1,t.$downloadBlob(e.url,e.file)}),(function(e){t.loading=!1}),this.year,this.exam)},getPDFByClass(){var t=this;this.pdfSelectOpen=!1,this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,y.getPDFByClass((function(e){t.loading=!1,t.$downloadBlob(e.url,e.file)}),(function(e){t.loading=!1}),this.year,this.exam)},getPDFByName(){var t=this;this.pdfSelectOpen=!1,this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,y.getPDFByName((function(e){t.loading=!1,t.$downloadBlob(e.url,e.file)}),(function(e){t.loading=!1}),this.year,this.exam)},getPDFBlank(){var t=this;this.pdfSelectOpen=!1,this.year=this.$store.getters["hod/activeYear"],this.exam=this.$store.getters["hod/activeExam"],this.loading=!0,y.getPDFBlank((function(e){t.loading=!1,t.$downloadBlob(e.url,e.file)}),(function(e){t.loading=!1}),this.year,this.exam)},wyapRank(t,e){var a="wyap_"+e+"_rank",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapGrade(t,e){var a="wyap_"+e+"_grade",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapPct(t,e){var a="wyap_"+e+"_pct",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapTotalsRank(t){var e="wyap_totals_rank",a=this.subject.students.find((function(e){return e.id===t}));if(a)return a[e]},wyapTotalsPct(t,e){var a="wyap_totals_pct",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapExtraTime(t,e){var a="wyap_"+e+"_hasUsedExtraTime",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapUnderperformed(t,e){var a="wyap_"+e+"_hasUnderperformed",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},wyapComment(t,e){var a="wyap_"+e+"_comment",s=this.subject.students.find((function(e){return e.id===t}));if(s)return s[a]},setWyap(t,e){this.selectedWyapId=t,e&&(this.selectedStudentId=e),this.swarmResults=this.subject.students.map((function(e){var a="wyap_"+t+"_";return{id:e.id,name:e.fullPreName,lastName:e.lastName,house:e.boardingHouse,pct:e[a+"pct"],percentage:e[a+"pct"],mark:e[a+"mark"],gender:e.gender,classCode:e.classCode,boarding:e.boardingHouse,rank:e[a+"rank"],comment:e[a+"comment"],hasUnderperformed:e[a+"hasUnderperformed"],hasUsedExtraTime:e[a+"hasUsedExtraTime"]}}));var a=this.subject.wyaps.find((function(e){return e.id===t}))||{};this.wyap=a,this.swarmTitle="totals"===t?"WYAP Totals":a.name}},computed:{visibleStudents(){var t=this;return 0===this.searchTerm.length?this.studentData:this.studentData.filter((function(e){return-1!==e.displayName.toLowerCase().indexOf(t.searchTerm.toLowerCase())}))},columns(){var t;return t=(this.subject.maxMLOCount,[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"Name",label:"Name",field:"displayName",type:"string",align:"left",sortable:!0},{name:"house",label:"House",field:"boardingHouseCode",type:"string",align:"left",sortable:!0},{name:"class",label:"Class",field:"classCode",type:"string",align:"left",sortable:!0}]),this.metrics.ALevelMock.length>0&&(t.push({name:"aLevelMockGrade",label:"U6 Mock",field:"aLevelMockGrade",type:"string",align:"left",sortable:!0}),t.push({name:"aLevelMockPercentage",label:"%",field:"aLevelMockPercentage",type:"string",align:"left",sortable:!0}),t.push({name:"aLevelMockRank",label:"Rank",field:"aLevelMockRank",type:"string",align:"left",sortable:!0})),this.metrics?(this.metrics.GCSE.length>0&&this.showBaselineData&&(t.push({name:"gcseGrade",label:"GCSE",field:"gcseGrade",type:"string",align:"left",sortable:!0}),t.push({name:"gcseGpa",label:"Gpa",field:"gcseGpa",type:"string",align:"left",sortable:!0})),this.metrics.IGDR.length,this.showBaselineData&&(this.subject.year>11?(t.push({name:"alisScore",label:"Alis",field:"alisBaseline",type:"string",align:"left",sortable:!0}),t.push({name:"alisPrediction",label:"Prediction",field:"alisPrediction",type:"string",align:"left",sortable:!0})):(t.push({name:"midyisScore",label:"Mdys",field:"midyisBaseline",type:"string",align:"left",sortable:!0}),t.push({name:"midyisPrediction",label:"Mdys Grade",field:"midyisPrediction",type:"string",align:"left",sortable:!0}))),t):t},showSwarm(){return!this.loading&&this.selectedWyapId&&this.swarmOpen},getSortBy(){return this.metrics&&this.metrics.ALevelMock.length>0?"aLevelMockRank":"set"},sorted(){var t=this.students.filter((function(t){return t.id>0}));return t.sort(this.$compare(this.sortKey,this.sortDir))},studentData(){var t=this,e=this.sorted.map((function(e){var a={id:e.id,displayName:e.displayName,classCode:e.classCode,boardingHouseCode:e.boardingHouseCode,schoolNumber:e.schoolNumber,midyisBaseline:e.midyisBaseline,midyisPrediction:e.midyisPrediction,alisPrediction:e.alisGcsePrediction,alisBaseline:e.alisGcseBaseline,gcseGpa:e.gcseGpa,gcseMockGpa:e.gcseMockGpa};if(!t.metrics)return a;var s=t.metrics.GCSEMock.find((function(t){return t.studentId===a.id}));s&&(a.gcseMockGrade=s.grade,a.gcseMockRank=s.cohortRank,a.gcseMockPercentage=s.percentage);var r=t.metrics.GCSE.find((function(t){return t.studentId===a.id}));r&&(a.gcseGrade=r.grade);var n=t.metrics.ALevelMock.find((function(t){return t.studentId===a.id}));n&&(a.aLevelMockGrade=n.grade,a.aLevelMockRank=n.cohortRank,a.aLevelMockPercentage=n.percentage);var i=t.metrics.IGDR.find((function(t){return t.studentId===a.id}));return i&&(a.gcseDelta=i.gcseDelta,a.IGDR=i.IGDR),a}));return e.sort(this.$compare(this.sortKey,this.sortDir))},columnCount(){return this.columns.filter((function(t){return!t.hidden})).length}},watch:{},components:{Swarm:ct,Loading:v["a"],ClassTable:lt["a"]},created(){var t=this;this.subject=this.$store.getters["user/getGlobalSubject"],this.getMetrics(),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.getMetrics),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.getMetrics),this.examWatch=this.$store.watch((function(){return t.$store.getters["hod/activeExam"]}),this.getMetrics)},beforeDestroy(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch(),this.examWatch&&this.examWatch()}},ut=dt,ht=(a("8d85"),a("3d3c")),gt=a("f987"),ft=a("e81c"),mt=a("ebe6"),pt=a("965d"),bt=Object(C["a"])(ut,U,K,!1,null,"28489db3",null),yt=bt.exports;function vt(t,e){var a=Object.keys(t);if(Object.getOwnPropertySymbols){var s=Object.getOwnPropertySymbols(t);e&&(s=s.filter((function(e){return Object.getOwnPropertyDescriptor(t,e).enumerable}))),a.push.apply(a,s)}return a}function wt(t){for(var e=1;e<arguments.length;e++){var a=null!=arguments[e]?arguments[e]:{};e%2?vt(Object(a),!0).forEach((function(e){c()(t,e,a[e])})):Object.getOwnPropertyDescriptors?Object.defineProperties(t,Object.getOwnPropertyDescriptors(a)):vt(Object(a)).forEach((function(e){Object.defineProperty(t,e,Object.getOwnPropertyDescriptor(a,e))}))}return t}G()(bt,"components",{QBar:at["a"],QInput:tt["a"],QIcon:j["a"],QBtn:rt["a"],QToggle:ht["a"],QSpace:st["a"],QScrollArea:et["a"],QMarkupTable:q["a"],QTooltip:D["a"],QBadge:gt["a"],QDialog:ft["a"],QCard:mt["a"],QCardSection:pt["a"]});var xt={name:"Page.HOD.Metrics",data(){return{elements_:[{name:"metrics",label:"All",component:yt},{name:"wyap",label:"wyap",component:g["a"],tooltip:"Whole Year Assessment Point"},{name:"history",label:"history",component:z}]}},computed:wt(wt(wt({},Object(l["c"])("user",["getGlobalSubject"])),Object(l["c"])("hod",["activeYear"])),{},{elements(){var t=[{name:"history",label:"history",component:z}];return t=[],11===this.activeYear||13===this.activeYear?[].concat(i()(this.elements_),i()(t)):this.elements_}}),methods:wt({},Object(l["d"])("hod",["setActiveYear"])),components:{toolbarPage:h["a"],YearBar:d["a"],ExamBar:u["a"]},created(){var t=this;this.setActiveYear(13),this.subjectWatch=this.$store.watch((function(){return t.$store.getters["user/getGlobalSubject"]}),this.setClass),this.yearWatch=this.$store.watch((function(){return t.$store.getters["hod/activeYear"]}),this.setClass)},beforeDestroy(){this.subjectWatch&&this.subjectWatch(),this.yearWatch&&this.yearWatch()}},kt=xt,_t=Object(C["a"])(kt,s,r,!1,null,null,null);e["default"]=_t.exports},f1e9:function(t,e,a){"use strict";var s=a("e4b7"),r=a.n(s);r.a},fa4e:function(t,e,a){},fbb2:function(t,e,a){}}]);