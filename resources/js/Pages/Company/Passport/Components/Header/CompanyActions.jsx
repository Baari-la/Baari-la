import { Send, Phone, GitCompareArrows, Share2, Heart } from "lucide-react";

export default function CompanyActions({
    onSendRFQ,
    onContact,
    onCompare,
    onShare,
    onSave,
}) {
    return (
        <div className="flex flex-wrap gap-3">
            <ActionButton
                icon={Send}
                title="Send RFQ"
                onClick={onSendRFQ}
                primary
            />

            <ActionButton
                icon={Phone}
                title="Contact Supplier"
                onClick={onContact}
            />

            <ActionButton
                icon={GitCompareArrows}
                title="Compare Supplier"
                onClick={onCompare}
            />

            <ActionButton
                icon={Share2}
                title="Share Passport"
                onClick={onShare}
            />

            <ActionButton icon={Heart} title="Save Supplier" onClick={onSave} />
        </div>
    );
}

function ActionButton({ icon: Icon, title, onClick, primary = false }) {
    return (
        <button
            onClick={onClick}
            className={`inline-flex items-center gap-2 rounded-xl px-5 py-3 text-sm font-semibold transition
                ${
                    primary
                        ? "bg-slate-900 text-white hover:bg-slate-800"
                        : "border border-slate-300 bg-white text-slate-700 hover:bg-slate-100"
                }`}
        >
            <Icon className="h-4 w-4" />

            {title}
        </button>
    );
}
