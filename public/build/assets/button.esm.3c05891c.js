import{D as i,l as m,m as f,o as s,e as o,j as y,n as r,k as c,d as v,g as p}from"./app.b53879ed.js";function C(t){t.addEventListener("mousedown",b)}function S(t){t.removeEventListener("mousedown",b)}function k(t){let e=document.createElement("span");e.className="p-ink",e.setAttribute("role","presentation"),t.appendChild(e),e.addEventListener("animationend",g)}function E(t){let e=h(t);e&&(S(t),e.removeEventListener("animationend",g),e.remove())}function b(t){let e=t.currentTarget,n=h(e);if(!n||getComputedStyle(n,null).display==="none")return;if(i.removeClass(n,"p-ink-active"),!i.getHeight(n)&&!i.getWidth(n)){let l=Math.max(i.getOuterWidth(e),i.getOuterHeight(e));n.style.height=l+"px",n.style.width=l+"px"}let d=i.getOffset(e),u=t.pageX-d.left+document.body.scrollTop-i.getWidth(n)/2,a=t.pageY-d.top+document.body.scrollLeft-i.getHeight(n)/2;n.style.top=a+"px",n.style.left=u+"px",i.addClass(n,"p-ink-active")}function g(t){i.removeClass(t.currentTarget,"p-ink-active")}function h(t){for(let e=0;e<t.children.length;e++)if(typeof t.children[e].className=="string"&&t.children[e].className.indexOf("p-ink")!==-1)return t.children[e];return null}const x={mounted(t,e){e.instance.$primevue&&e.instance.$primevue.config&&e.instance.$primevue.config.ripple&&(k(t),C(t))},unmounted(t){E(t)}};var L={name:"Button",props:{label:{type:String},icon:{type:String},iconPos:{type:String,default:"left"},iconClass:{type:String,default:null},badge:{type:String},badgeClass:{type:String,default:null},loading:{type:Boolean,default:!1},loadingIcon:{type:String,default:"pi pi-spinner pi-spin"}},computed:{buttonClass(){return{"p-button p-component":!0,"p-button-icon-only":this.icon&&!this.label,"p-button-vertical":(this.iconPos==="top"||this.iconPos==="bottom")&&this.label,"p-disabled":this.$attrs.disabled||this.loading,"p-button-loading":this.loading,"p-button-loading-label-only":this.loading&&!this.icon&&this.label}},iconStyleClass(){return[this.loading?"p-button-loading-icon "+this.loadingIcon:this.icon,"p-button-icon",this.iconClass,{"p-button-icon-left":this.iconPos==="left"&&this.label,"p-button-icon-right":this.iconPos==="right"&&this.label,"p-button-icon-top":this.iconPos==="top"&&this.label,"p-button-icon-bottom":this.iconPos==="bottom"&&this.label}]},badgeStyleClass(){return["p-badge p-component",this.badgeClass,{"p-badge-no-gutter":this.badge&&String(this.badge).length===1}]},disabled(){return this.$attrs.disabled||this.loading},defaultAriaLabel(){return this.label?this.label+(this.badge?" "+this.badge:""):this.$attrs["aria-label"]}},directives:{ripple:x}};const P=["aria-label","disabled"],_={class:"p-button-label"};function D(t,e,n,d,u,a){const l=m("ripple");return f((s(),o("button",{class:r(a.buttonClass),type:"button","aria-label":a.defaultAriaLabel,disabled:a.disabled},[y(t.$slots,"default",{},()=>[n.loading&&!n.icon?(s(),o("span",{key:0,class:r(a.iconStyleClass)},null,2)):c("",!0),n.icon?(s(),o("span",{key:1,class:r(a.iconStyleClass)},null,2)):c("",!0),v("span",_,p(n.label||"\xA0"),1),n.badge?(s(),o("span",{key:2,class:r(a.badgeStyleClass)},p(n.badge),3)):c("",!0)])],10,P)),[[l]])}L.render=D;export{x as R,L as s};