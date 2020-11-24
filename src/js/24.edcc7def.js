(window["webpackJsonp"]=window["webpackJsonp"]||[]).push([[24],{5879:function(t,e,a){"use strict";var s=a("7e52"),l=a.n(s);l.a},"7e52":function(t,e,a){},8426:function(t,e,a){"use strict";a.r(e);var s=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("toolbar-page",{attrs:{elements:t.elements,default:"jobs"}})},l=[],o=a("08e9"),i=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",{staticClass:"row fitx q-mt-md"},[a("div",{staticClass:"col q-gutter-md column flex justify-between"},[a("q-card",{attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-y-md"},[a("q-input",{attrs:{dense:"",color:"font",outlined:"",label:"Name","stack-label":""},model:{value:t.job_.name,callback:function(e){t.$set(t.job_,"name",e)},expression:"job_.name"}}),a("q-input",{attrs:{dense:"",color:"font",outlined:"",label:"Description","stack-label":"",type:"textarea"},model:{value:t.job_.description,callback:function(e){t.$set(t.job_,"description",e)},expression:"job_.description"}})],1)],1),a("q-card",{staticClass:"q-pt-sm",attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-y-md"},[t._v("\n          Preset Frequency\n         "),a("q-select",{attrs:{dense:"",color:"font",outlined:"",options:t.freqOptions,label:"Preset Frequency"},model:{value:t.job_.preset_freq,callback:function(e){t.$set(t.job_,"preset_freq",e)},expression:"job_.preset_freq"}})],1),a("q-card-section",{staticClass:"bg-tertiary q-gutter-y-md"},[t._v("\n         Custom Frequency\n         "),a("div",{staticClass:"row full-width justify-center q-gutter-x-xs"},[a("q-badge",{staticClass:"col bg-positive text-black text-center text-bold",attrs:{color:"postive"}},[t._v("Every")]),a("q-input",{attrs:{dense:"",color:"font",outlined:"",placeholder:"Digit","stack-label":""},model:{value:t.job_.unit_value,callback:function(e){t.$set(t.job_,"unit_value",e)},expression:"job_.unit_value"}}),a("q-select",{staticClass:"col-5",attrs:{dense:"",color:"font",outlined:"",options:t.unitKeyOptions},model:{value:t.job_.unit_key,callback:function(e){t.$set(t.job_,"unit_key",e)},expression:"job_.unit_key"}})],1),a("p",{staticClass:"text-font"},[t._v("This will override the preset frequency.")])])],1),a("q-card",{staticClass:"q-pt-sm",attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-y-md"},[t._v("\n         Run At\n         "),a("div",{staticClass:"row full-width justify-center q-gutter-x-xs"},[a("q-input",{staticClass:"col",attrs:{outlined:"",dense:"",mask:"time",placeholder:"HH:mm",color:"font"},model:{value:t.job_.run_at,callback:function(e){t.$set(t.job_,"run_at",e)},expression:"job_.run_at"}}),a("q-select",{staticClass:"col",attrs:{dense:"",color:"font",outlined:"","emit-value":"","map-options":"",options:t.dayOfWeekOptions},model:{value:t.job_.day_of_week,callback:function(e){t.$set(t.job_,"day_of_week",e)},expression:"job_.day_of_week"}})],1)])],1)],1),a("div",{staticClass:"col q-ml-lg"},[a("q-card",{attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-md"},[t._v("\n           Wake Up On\n           "),a("div",{staticClass:"row full-width justify-center q-gutter-x-xs"},[a("q-input",{staticClass:"col",attrs:{outlined:"",dense:"",mask:"date",placeholder:"YYYY/MM/DD",color:"font"},model:{value:t.job_.up_date,callback:function(e){t.$set(t.job_,"up_date",e)},expression:"job_.up_date"}}),a("q-input",{staticClass:"col",attrs:{outlined:"",dense:"",mask:"time",placeholder:"HH:mm",color:"font"},model:{value:t.job_.up_time,callback:function(e){t.$set(t.job_,"up_time",e)},expression:"job_.up_time"}})],1),a("br"),t._v("\n           Sleep On\n           "),a("div",{staticClass:"row full-width justify-center q-gutter-x-xs"},[a("q-input",{staticClass:"col",attrs:{outlined:"",dense:"",mask:"date",placeholder:"YYYY/MM/DD",color:"font"},model:{value:t.job_.sleep_date,callback:function(e){t.$set(t.job_,"sleep_date",e)},expression:"job_.sleep_date"}}),a("q-input",{staticClass:"col",attrs:{outlined:"",dense:"",mask:"time",placeholder:"HH:mm",color:"font"},model:{value:t.job_.sleep_time,callback:function(e){t.$set(t.job_,"sleep_time",e)},expression:"job_.sleep_time"}})],1)])],1),a("br"),a("q-card",{staticClass:"q-pt-sm",attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-md"},[t._v("\n          State\n          "),a("div",{staticClass:"row"},[a("q-toggle",{staticClass:"font-secondary col",attrs:{"false-value":0,"true-value":1,label:"Sleep during holidays and exeats",c:"",olor:"positive"},model:{value:t.job_.term_time_only,callback:function(e){t.$set(t.job_,"term_time_only",e)},expression:"job_.term_time_only"}}),a("q-toggle",{staticClass:"font-secondary col",attrs:{"false-value":0,"true-value":1,label:"Prevent Overlapping",color:"positive"},model:{value:t.job_.prevent_overlapping,callback:function(e){t.$set(t.job_,"prevent_overlapping",e)},expression:"job_.prevent_overlapping"}})],1),a("div",{staticClass:"row"},[a("q-toggle",{staticClass:"font-secondary col",attrs:{"false-value":0,"true-value":1,label:"Task Is Active",color:"positive"},model:{value:t.job_.active,callback:function(e){t.$set(t.job_,"active",e)},expression:"job_.active"}})],1)])],1),a("br"),a("q-card",{staticClass:"q-pt-sm",attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-md"},[t._v("\n          Script\n          "),a("q-select",{staticClass:"col",attrs:{dense:"",color:"font",outlined:"",options:t.scriptOptions},model:{value:t.job_.script,callback:function(e){t.$set(t.job_,"script",e)},expression:"job_.script"}}),a("div",{staticClass:"row"},[t._v(t._s(t.scriptPath))])],1)],1),a("br"),a("q-card",{staticClass:"q-pt-sm",attrs:{outlined:""}},[a("q-card-section",{staticClass:"bg-tertiary q-gutter-md"},[t.job?a("div",{staticClass:"row q-gutter-x-sm"},[a("q-btn",{staticClass:"text-positive col",attrs:{label:"save",outline:"",color:"positive"},on:{click:t.saveJob}}),a("q-btn",{staticClass:"text-positive col",attrs:{label:"delete",outline:"",color:"warning"},on:{click:t.deleteJob}})],1):t._e(),t.job?t._e():a("div",{staticClass:"row q-gutter-x-sm"},[a("q-btn",{staticClass:"text-positive col",attrs:{label:"create",outline:"",color:"positive",disabled:t.btnDisabled},on:{click:t.createJob}})],1)])],1)],1)])},n=[],r=(a("c880"),a("ebdc")),c={name:"PageResults",props:{job:{type:Object}},data(){return{job_:{id:null,name:"",description:"",preset_freq:null,unit_value:null,unit_key:"hour",run_at:null,day_of_week:null,up_time:null,up_date:null,sleep_time:null,sleep_date:null,term_time_only:0,prevent_overlapping:1,active:0,script:null,run_in:null},freqOptions:[null,"hourly","daily","yearly","quarterly","yearly"],unitKeyOptions:["hour","minutes","day","month"],dayOfWeekOptions:[{label:"All",value:null},{label:"Mon-Fri",value:"weekdays"},{label:"Monday",value:"1"},{label:"Tuesday",value:"2"},{label:"Wednesday",value:"3"},{label:"Thursday",value:"4"},{label:"Friday",value:"5"},{label:"Saturday",value:"6"},{label:"Sunday",value:"7"}],scripts:[]}},methods:{createJob(){var t=this;this.job_.run_in=this.scriptPath,this.job_.unit_value&&0===this.job_.unit_value.length&&(this.job_.unit_value=null),r["a"].postJob((function(e){t.returnToJobs()}),this.$errorHandler,this.job_)},saveJob(){var t=this;this.job_.run_in=this.scriptPath,this.job_.unit_value&&0===this.job_.unit_value.length&&(this.job_.unit_value=null),r["a"].putJob((function(e){t.returnToJobs()}),this.$errorHandler,this.job_)},deleteJob(){var t=this;r["a"].deleteJob((function(e){t.returnToJobs()}),this.$errorHandler,this.job_.id)},returnToJobs(){this.job?this.$emit("close"):this.$router.push({path:"/admin/jobs"})}},computed:{scriptOptions(){return this.scripts.map((function(t){return t.file}))},scriptPath(){var t=this;if(!this.job_.script)return"";var e=this.scripts.find((function(e){return e.file===t.job_.script}));return e?e.path:""},btnDisabled(){return 0===this.job_.name.length}},components:{},created(){var t=this;r["a"].getScripts((function(e){t.scripts=e}),this.$errorHandler),this.job&&(this.job_=this.job)}},u=c,d=(a("dc59"),a("2be6")),b=a("8c42"),p=a("ebe6"),_=a("965d"),m=a("bc74"),v=a("3946"),f=a("f987"),j=a("3d3c"),h=a("2ef0"),q=a("e279"),g=a.n(q),y=Object(d["a"])(u,i,n,!1,null,"36964244",null),C=y.exports;g()(y,"components",{QPage:b["a"],QCard:p["a"],QCardSection:_["a"],QInput:m["a"],QSelect:v["a"],QBadge:f["a"],QToggle:j["a"],QBtn:h["a"]});var k=function(){var t=this,e=t.$createElement,a=t._self._c||e;return a("div",[a("crud",{ref:"crud",attrs:{api:t.api,columns:t.columns,search:"",sortBy:"name",reverse:"",channel:"admin.jobs",indicator:"active",actions:t.actions},on:{editJob:t.editJob}}),a("q-dialog",{attrs:{maximized:""},model:{value:t.editOpen,callback:function(e){t.editOpen=e},expression:"editOpen"}},[a("q-card",{staticClass:"bg-secondary text-white"},[a("q-bar",{staticClass:"bg-tertiary"},[a("q-space"),a("q-btn",{directives:[{name:"close-popup",rawName:"v-close-popup"}],attrs:{dense:"",flat:"",icon:"close"}},[a("q-tooltip",{attrs:{"content-class":"bg-white text-primary"}},[t._v("Close")])],1)],1),a("br"),a("q-card-section",{staticClass:"q-pt-none"},[a("job",{attrs:{job:t.currentJob},on:{close:t.closeEdit}})],1)],1)],1)],1)},x=[],w=a("d612"),J={name:"Component.Admin.Logs.Access",data(){return{api:{get:r["a"].getJobs},columns:[{name:"id",label:"id",field:"id",type:"string",align:"left",hidden:!0},{name:"name",label:"Name",field:"name",type:"string",align:"left",sortable:!0},{name:"script",label:"Script",field:"script",type:"string",align:"left",sortable:!0},{name:"last_run",label:"Last Run",field:"last_run",type:"string",align:"left",sortable:!0}],actions:[{title:"Edit",event:"editJob",icon:"fal fa-edit"}],currentJob:null,editOpen:!1}},methods:{editJob(t){this.currentJob=t,this.editOpen=!0},closeEdit(){this.editOpen=!1,this.$refs.crud.get()}},computed:{},components:{Crud:w["a"],Job:C},created(){}},$=J,O=(a("5879"),a("e81c")),Q=a("5d16"),P=a("f962c"),S=a("3aaf"),H=a("58c0"),T=Object(d["a"])($,k,x,!1,null,"47bc2f8d",null),D=T.exports;g()(T,"components",{QDialog:O["a"],QCard:p["a"],QBar:Q["a"],QSpace:P["a"],QBtn:h["a"],QTooltip:S["a"],QCardSection:_["a"]}),g()(T,"directives",{ClosePopup:H["a"]});var Y={name:"PageAdminJobs",data(){return{elements:[{name:"jobs",label:"Jobs",component:D},{name:"new",label:"New Job",component:C}]}},components:{toolbarPage:o["a"]},methods:{refresh(){}}},E=Y,A=Object(d["a"])(E,s,l,!1,null,null,null);e["default"]=A.exports},a7e5:function(t,e,a){},dc59:function(t,e,a){"use strict";var s=a("a7e5"),l=a.n(s);l.a}}]);