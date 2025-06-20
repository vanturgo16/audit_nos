function scrollToNavItem(navItemId, routePattern) {
    if (window.location.pathname.includes(routePattern)) {
        const targetElement = document.getElementById(navItemId);
        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: "smooth",
                block: "center"
            });
        }
    }
}

document.addEventListener("DOMContentLoaded", function () {
    // Scroll target elements based on current URL
    scrollToNavItem("dashboardNav", "dashboard");
    scrollToNavItem("userNav", "user");
    scrollToNavItem("dropdownNav", "dropdown");
    scrollToNavItem("gradNav", "grading");
    scrollToNavItem("ruleNav", "rule");
    scrollToNavItem("schedulerNav", "scheduler");
    scrollToNavItem("jaringanNav", "jaringan");
    scrollToNavItem("empNav", "employee");
    scrollToNavItem("deptNav", "department");
    scrollToNavItem("positionNav", "position");
    scrollToNavItem("parentCheckNav", "parentchecklist");
    scrollToNavItem("checklistNav", "checklist");
    scrollToNavItem("mappingCheckNav", "mapchecklist");
    scrollToNavItem("periodNameNav", "periodname");
    scrollToNavItem("periodNav", "periodchecklist");
    scrollToNavItem("reviewNav", "review");
    scrollToNavItem("assignedNav", "auditor");
    scrollToNavItem("auditlogNav", "auditlog");
});
