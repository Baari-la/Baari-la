export default function WidgetToolbar({
    left,

    right,
}) {
    return (
        <div className="mb-6 flex items-center justify-between">
            <div>{left}</div>

            <div className="flex items-center gap-3">{right}</div>
        </div>
    );
}
