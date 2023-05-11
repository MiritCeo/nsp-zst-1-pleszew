import{l as C,o as r,e as m,d as i,F as v,n as d,g,c as u,E as w,m as T,k as E,G as l,Z as h,O as _,U as S,q as y,a as b,b as I,f as A,I as $,s as B}from"./app.b53879ed.js";import{R as G}from"./button.esm.3c05891c.js";import{s as R}from"./portal.esm.e4d18dea.js";var x={name:"ToastMessage",emits:["close"],props:{message:null,template:null},closeTimeout:null,mounted(){this.message.life&&(this.closeTimeout=setTimeout(()=>{this.close()},this.message.life))},beforeUnmount(){this.clearCloseTimeout()},methods:{close(){this.$emit("close",this.message)},onCloseClick(){this.clearCloseTimeout(),this.close()},clearCloseTimeout(){this.closeTimeout&&(clearTimeout(this.closeTimeout),this.closeTimeout=null)}},computed:{containerClass(){return["p-toast-message",this.message.styleClass,{"p-toast-message-info":this.message.severity==="info","p-toast-message-warn":this.message.severity==="warn","p-toast-message-error":this.message.severity==="error","p-toast-message-success":this.message.severity==="success"}]},iconClass(){return["p-toast-message-icon pi",{"pi-info-circle":this.message.severity==="info","pi-exclamation-triangle":this.message.severity==="warn","pi-times":this.message.severity==="error","pi-check":this.message.severity==="success"}]}},directives:{ripple:G}};const Z={class:"p-toast-message-text"},L={class:"p-toast-summary"},M={class:"p-toast-detail"},N=i("span",{class:"p-toast-icon-close-icon pi pi-times"},null,-1),U=[N];function z(e,s,t,n,a,o){const p=C("ripple");return r(),m("div",{class:d(o.containerClass),role:"alert","aria-live":"assertive","aria-atomic":"true"},[i("div",{class:d(["p-toast-message-content",t.message.contentStyleClass])},[t.template?(r(),u(w(t.template),{key:1,message:t.message},null,8,["message"])):(r(),m(v,{key:0},[i("span",{class:d(o.iconClass)},null,2),i("div",Z,[i("span",L,g(t.message.summary),1),i("div",M,g(t.message.detail),1)])],64)),t.message.closable!==!1?T((r(),m("button",{key:2,class:"p-toast-icon-close p-link",onClick:s[0]||(s[0]=(...c)=>o.onCloseClick&&o.onCloseClick(...c)),type:"button"},U)),[[p]]):E("",!0)],2)],2)}x.render=z;var P=0,j={name:"Toast",inheritAttrs:!1,props:{group:{type:String,default:null},position:{type:String,default:"top-right"},autoZIndex:{type:Boolean,default:!0},baseZIndex:{type:Number,default:0},breakpoints:{type:Object,default:null}},data(){return{messages:[]}},styleElement:null,mounted(){l.on("add",this.onAdd),l.on("remove-group",this.onRemoveGroup),l.on("remove-all-groups",this.onRemoveAllGroups),this.breakpoints&&this.createStyle()},beforeUnmount(){this.destroyStyle(),this.$refs.container&&this.autoZIndex&&h.clear(this.$refs.container),l.off("add",this.onAdd),l.off("remove-group",this.onRemoveGroup),l.off("remove-all-groups",this.onRemoveAllGroups)},methods:{add(e){e.id==null&&(e.id=P++),this.messages=[...this.messages,e]},remove(e){let s=-1;for(let t=0;t<this.messages.length;t++)if(this.messages[t]===e){s=t;break}this.messages.splice(s,1)},onAdd(e){this.group==e.group&&this.add(e)},onRemoveGroup(e){this.group===e&&(this.messages=[])},onRemoveAllGroups(){this.messages=[]},onEnter(){this.$refs.container.setAttribute(this.attributeSelector,""),this.autoZIndex&&h.set("modal",this.$refs.container,this.baseZIndex||this.$primevue.config.zIndex.modal)},onLeave(){this.$refs.container&&this.autoZIndex&&_.isEmpty(this.messages)&&h.clear(this.$refs.container)},createStyle(){if(!this.styleElement){this.styleElement=document.createElement("style"),this.styleElement.type="text/css",document.head.appendChild(this.styleElement);let e="";for(let s in this.breakpoints){let t="";for(let n in this.breakpoints[s])t+=n+":"+this.breakpoints[s][n]+"!important;";e+=`
                        @media screen and (max-width: ${s}) {
                            .p-toast[${this.attributeSelector}] {
                                ${t}
                            }
                        }
                    `}this.styleElement.innerHTML=e}},destroyStyle(){this.styleElement&&(document.head.removeChild(this.styleElement),this.styleElement=null)}},computed:{containerClass(){return["p-toast p-component p-toast-"+this.position,{"p-input-filled":this.$primevue.config.inputStyle==="filled","p-ripple-disabled":this.$primevue.config.ripple===!1}]},attributeSelector(){return S()}},components:{ToastMessage:x,Portal:R}};function D(e,s,t,n,a,o){const p=y("ToastMessage"),c=y("Portal");return r(),u(c,null,{default:b(()=>[i("div",B({ref:"container",class:o.containerClass},e.$attrs),[I($,{name:"p-toast-message",tag:"div",onEnter:o.onEnter,onLeave:o.onLeave},{default:b(()=>[(r(!0),m(v,null,A(a.messages,f=>(r(),u(p,{key:f.id,message:f,onClose:s[0]||(s[0]=k=>o.remove(k)),template:e.$slots.message},null,8,["message","template"]))),128))]),_:1},8,["onEnter","onLeave"])],16)]),_:1})}function X(e,s){s===void 0&&(s={});var t=s.insertAt;if(!(!e||typeof document>"u")){var n=document.head||document.getElementsByTagName("head")[0],a=document.createElement("style");a.type="text/css",t==="top"&&n.firstChild?n.insertBefore(a,n.firstChild):n.appendChild(a),a.styleSheet?a.styleSheet.cssText=e:a.appendChild(document.createTextNode(e))}}var O=`
.p-toast {
    position: fixed;
    width: 25rem;
}
.p-toast-message-content {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: start;
        -ms-flex-align: start;
            align-items: flex-start;
}
.p-toast-message-text {
    -webkit-box-flex: 1;
        -ms-flex: 1 1 auto;
            flex: 1 1 auto;
}
.p-toast-top-right {
    top: 20px;
    right: 20px;
}
.p-toast-top-left {
    top: 20px;
    left: 20px;
}
.p-toast-bottom-left {
    bottom: 20px;
    left: 20px;
}
.p-toast-bottom-right {
    bottom: 20px;
    right: 20px;
}
.p-toast-top-center {
    top: 20px;
    left: 50%;
    -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
}
.p-toast-bottom-center {
    bottom: 20px;
    left: 50%;
    -webkit-transform: translateX(-50%);
            transform: translateX(-50%);
}
.p-toast-center {
    left: 50%;
    top: 50%;
    min-width: 20vw;
    -webkit-transform: translate(-50%, -50%);
            transform: translate(-50%, -50%);
}
.p-toast-icon-close {
    display: -webkit-box;
    display: -ms-flexbox;
    display: flex;
    -webkit-box-align: center;
        -ms-flex-align: center;
            align-items: center;
    -webkit-box-pack: center;
        -ms-flex-pack: center;
            justify-content: center;
    overflow: hidden;
    position: relative;
}
.p-toast-icon-close.p-link {
    cursor: pointer;
}

/* Animations */
.p-toast-message-enter-from {
    opacity: 0;
    -webkit-transform: translateY(50%);
    transform: translateY(50%);
}
.p-toast-message-leave-from {
    max-height: 1000px;
}
.p-toast .p-toast-message.p-toast-message-leave-to {
    max-height: 0;
    opacity: 0;
    margin-bottom: 0;
    overflow: hidden;
}
.p-toast-message-enter-active {
    -webkit-transition: transform 0.3s, opacity 0.3s;
    -webkit-transition: opacity 0.3s, -webkit-transform 0.3s;
    transition: opacity 0.3s, -webkit-transform 0.3s;
    transition: transform 0.3s, opacity 0.3s;
    transition: transform 0.3s, opacity 0.3s, -webkit-transform 0.3s;
}
.p-toast-message-leave-active {
    -webkit-transition: max-height 0.45s cubic-bezier(0, 1, 0, 1), opacity 0.3s, margin-bottom 0.3s;
    transition: max-height 0.45s cubic-bezier(0, 1, 0, 1), opacity 0.3s, margin-bottom 0.3s;
}
`;X(O);j.render=D;export{j as s};
