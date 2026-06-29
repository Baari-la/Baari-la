import Panel from "../Common/Layout/Panel";

export default function DashboardWidget({
    children,

    className = "",
}) {
    return <Panel className={className}>{children}</Panel>;
}
