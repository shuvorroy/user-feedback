import {
    useBlockProps,
    RichText,
    InspectorControls
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import {
    TextControl,
    ToggleControl,
    PanelBody,
    PanelRow
} from '@wordpress/components';

export default function Edit({ attributes, setAttributes }) {

    const blockProps = useBlockProps();

    return (
        <>
            <InspectorControls>
                <PanelBody title="Form Settings" initialOpen={false}>
                    <PanelRow>
                        <TextControl
                            label="Button Text"
                            onChange={(buttonText) => setAttributes({ buttonText })}
                            value={attributes.buttonText}
                        />
                    </PanelRow>
                </PanelBody>
            </InspectorControls>
            <div {...blockProps}>
                <form action="" className='user-feedback' encType="multipart/form-data">
                    <RichText
                        {...blockProps}
                        tagName="h3"
                        onChange={(formTitle) => setAttributes({ formTitle })}
                        value={attributes.formTitle}
                    />
                    <div className="two-cols">
                        <label>
                            {__('First Name *', 'user-feedback')}
                            <input type="text" name="firstname" disabled />
                        </label>
                        <label>
                            {__('Last Name *', 'user-feedback')}
                            <input type="text" name="lastname" disabled />
                        </label>
                    </div>
                    <div className="one-cols">
                        <label>
                            {__('Email *', 'user-feedback')}
                            <input type="email" name="email" disabled />
                        </label>
                    </div>
                    <div className="one-cols">
                        <label>
                            {__('Subject *', 'user-feedback')}
                            <input type="text" name="subject" disabled />
                        </label>
                    </div>
                    <div className="one-cols">
                        <label>
                            {__('Message *', 'user-feedback')}
                        </label>
                        <textarea type="text" name="message" disabled defaultValue={""} />
                    </div>
                    <div className="btns">
                        <button type='submit'>{attributes.buttonText}</button>
                    </div>
                </form>
            </div>
        </>
    )
}