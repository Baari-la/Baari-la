export default function CompanySectionHeader({ title, subtitle, button }) {
    return (
        <div className="flex justify-between items-center mb-6">
            <div>
                <h3 className="text-yellow-400 text-xs font-black uppercase tracking-[0.3em]">
                    {title}
                </h3>

                {subtitle && (
                    <p className="text-[10px] text-slate-500 mt-1">
                        {subtitle}
                    </p>
                )}
            </div>

            {button}
        </div>
    );
}
